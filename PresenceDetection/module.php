<?php
	class PresenceDetection extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			
			//Properties
			$this -> RegisterPropertyString("MotionSensors", '[]');

			//StatusVariables
			$this->RegisterVariableBoolean('Alert', $this->Translate('Alert'), '~Alert', 30);
			$this -> RegisterVariableString('ActiveSensors', 'Active Sensors', '~TextBox', 40);

			//Attributes
		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();

			$sensors = json_decode($this->ReadPropertyString('MotionSensors'));
		
			//Update active sensors
			$this->updateActive();
		
			//Deleting all References
            foreach ($this->GetReferenceList() as $referenceID) {
                $this->UnregisterReference($referenceID);
			}
			
			//Adding references for targets
            foreach ($sensors as $sensor) {
                $this->RegisterMessage($sensor->VariableID, VM_UPDATE);
                $this->RegisterReference($sensor->VariableID);
            }
		}

		//Module Functions
		public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
        {
			$sensors = json_decode($this->ReadPropertyString('MotionSensors'));
			
			$this->SendDebug('MessageSink', 'SenderID: ' . $SenderID . ', Message: ' . $Message, 0);

            foreach ($sensors as $sensor) {
                if ($sensor->VariableID == $SenderID) {
                    $this->TriggerAlert($sensor->VariableID, GetValue($sensor->VariableID));
                    $this->updateActive();
                    return;
                }
            }
		}
		
        public function TriggerAlert(int $SourceID, $SourceValue)
        {
            SetValue($this->GetIDForIdent('Alert'), True);
        }

        private function updateActive()
        {
			$sensors = json_decode($this->ReadPropertyString('MotionSensors'), true);

			$activeSensors = '';
            foreach ($sensors as $sensor) {
                $sensorID = $sensor['VariableID'];
                $activeSensors .= '- ' . IPS_GetLocation($sensorID) . "\n";
            }
            if ($activeSensors == '') {
                IPS_SetHidden($this->GetIDForIdent('ActiveSensors'), true);
                return;
            }

            $this->SetValue('ActiveSensors', $activeSensors);
            IPS_SetHidden($this->GetIDForIdent('ActiveSensors'), false);
		}
		
		public function RequestAction($Ident, $Value)
		{
			switch ($Ident) {
                default:
                    throw new Exception('Invalid ident');
            }
		}

	}
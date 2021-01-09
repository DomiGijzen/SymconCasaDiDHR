<?php
	class PresenceDetection extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			
			//Properties
			$this -> RegisterPropertyString("MotionSensors", '[]');
			//StatusVariables
			$this -> RegisterVariableString('ActiveSensors', 'Active Sensors', '~TextBox', 40);
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
        private function updateActive()
        {
            $sensors = json_decode($this->ReadPropertyString('MotionSensors'), true);

            $activeSensors = '';
            foreach ($sensors as $sensor) {
                $sensorID = $sensor['VariableID'];
                if ($this->getAlertValue($sensorID, GetValue($sensorID))) {
                    $activeSensors .= '- ' . IPS_GetLocation($sensorID) . "\n";
                }
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
		}

	}
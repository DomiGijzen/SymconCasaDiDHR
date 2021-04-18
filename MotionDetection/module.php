<?php
	class MotionDetection extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			
			//Properties
			$this -> RegisterPropertyInteger("MotionSensorType", 0);
			$this -> RegisterPropertyInteger("MotionSensor", 0);

			//StatusVariables
			$this -> RegisterVariableBoolean("MomentaryPresence", "Momentary presence", "~Presence", 10);
			$this -> RegisterVariableInteger("LastDetection", "Last detection", "~UnixTimestamp", 20);
			$this -> RegisterVariableInteger("LastMotionDuration", "Last motion duration", "", 30);
			$this -> RegisterVariableBoolean("InitiatesPresence", "Initiates presence", "", 40);
			$this -> RegisterVariableInteger("TimeOut", "Time Out", "", 50);
			$this -> RegisterVariableInteger("MinimumMotionDuration", "Minimum motion duration", "", 60);

			//Attributes

			//Timers
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

			$sensor = $this -> ReadPropertyInteger('MotionSensor');
		
			//Update active sensors
			$this -> updateActive();
		
			//Deleting all References
            foreach ($this->GetReferenceList() as $referenceID) {
                $this -> UnregisterReference($referenceID);
			}
			
			//Adding references for targets
                        $this -> RegisterMessage($sensor -> VariableID, VM_UPDATE);
            $this -> RegisterReference($sensor -> VariableID);
		}

		//Module Functions
		public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
        {
			$sensor = $this->ReadPropertyInteger('MotionSensor');
			
			$this -> SendDebug('MessageSink', 'SenderID: ' . $SenderID . ', Message: ' . $Message, 0);

                if ($sensor -> VariableID == $SenderID) {
                    $this -> TriggerMotion($sensor -> VariableID, GetValue($sensor -> VariableID));
                    $this -> updateActive();
                    return;
                }
		}
		
        public function TriggerMotion(int $SourceID, $SourceValue)
        {
			SetValue($this -> GetIDForIdent('Motion'), True);
			$this->SetTimerInterval("Delay", 5000);
		}
		
		public function ResetMotion()
		{
            SetValue($this -> GetIDForIdent('Motion'), False);
		}

        private function updateActive()
        {
			$sensor = $this -> ReadPropertyInteger('MotionSensor');

			$activeSensors = '';
                $sensorID = $sensor['VariableID'];
                $activeSensors .= '- ' . IPS_GetLocation($sensorID) . "\n";
            if ($activeSensors == '') {
                IPS_SetHidden($this -> GetIDForIdent('ActiveSensors'), true);
                return;
            }

            $this -> SetValue('ActiveSensors', $activeSensors);
            IPS_SetHidden($this -> GetIDForIdent('ActiveSensors'), false);
		}
		
		public function RequestAction($Ident, $Value)
		{
			switch ($Ident) {
                default:
                    throw new Exception('Invalid ident');
            }
		}

	}
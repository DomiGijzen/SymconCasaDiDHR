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
			$this -> RegisterTimer('Delay', 5000, 'MDM_ResetMotion($_IPS[\'TARGET\']);');
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
		
		
			//Deleting all References
            foreach ($this->GetReferenceList() as $referenceID) {
                $this -> UnregisterReference($referenceID);
			}
			
			//Adding references for targets
            $this -> RegisterMessage($sensor, VM_UPDATE);
            $this -> RegisterReference($sensor);
		}

		//Module Functions
		public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
        {
			$sensor = $this->ReadPropertyInteger('MotionSensor');
			
			$this -> SendDebug('MessageSink', 'SenderID: ' . $SenderID . ', Message: ' . $Message, 0);

                if ($sensor == $SenderID) {
                    $this -> TriggerMotion($sensor, GetValue($sensor));
                    return;
                }
		}
		
        public function TriggerMotion(int $SourceID, $SourceValue)
        {
			SetValue($this -> GetIDForIdent('MomentaryPresence'), True);
			$this->SetTimerInterval("Delay", 5000);
		}
		
		public function ResetMotion()
		{
            SetValue($this -> GetIDForIdent('MomentaryPresence'), False);
		}

		public function RequestAction($Ident, $Value)
		{
			switch ($Ident) {
                default:
                    throw new Exception('Invalid ident');
            }
		}

	}
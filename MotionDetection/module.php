<?php
	class MotionDetection extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			
			//Properties

			//StatusVariables
			$this -> RegisterVariableBoolean("MomentaryPresence", "Momentary presence", "~Presence", 10);
			$this -> RegisterVariableInteger("LastDetection", "Last detection", "~UnixTimeStamp" 20);
			$this -> RegisterVariableInteger("LastMotionDuration", "Last motion duration", "", 30));

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

		}

		//Module Functions
		
		public function RequestAction($Ident, $Value)
		{
			switch ($Ident) {
                default:
                    throw new Exception('Invalid ident');
            }
		}

	}
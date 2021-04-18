<?php
	class MotionDetection extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			
			//Properties

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
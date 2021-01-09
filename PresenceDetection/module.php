<?php
	class PresenceDetection extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			
			//Properties
			$this -> RegisterPropertyString("MotoionSensors", "")
			//StatusVariables
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
		}

	}
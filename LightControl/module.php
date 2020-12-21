<?php
	class LightControl extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			
			//Properties
			$this -> RegisterPropertyInteger("DimmerType", 0);
			$this -> RegisterPropertyInteger("DimmerInstance", 0);
			$this -> RegisterPropertyInteger("DimmerChannel", 0);
			$this -> RegisterPropertyInteger("StartPercentage", 0);
			$this -> RegisterPropertyInteger("StdPercentage", 50);
			$this -> RegisterPropertyInteger("StdDimTime", 1);

			//StatusVariables
			$this->RegisterVariableBoolean("Status", "Lamp Aan/Uit", "~Switch");
			$this->RegisterVariableInteger("Dim", "Lamp Dim stand", "DimLedSpot");
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
		public function SwitchLight()
		{
			$DimmerInstance = $this->ReadPropertyInteger("DimmerInstance");
			PHUE_SwitchMode($DimmerInstance, 1);
		}

	}
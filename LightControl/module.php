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
		public function SwitchLight($DesiredState = 99)
		{
			$DimmerInstance = $this->ReadPropertyInteger("DimmerInstance");
			
			$StateVariableId = @IPS_GetVariableIDByName("State", $DimmerInstance);
			
			$CurrentState = IPS_GetVariable($StateVariableId)

			switch($CurrentState)
			{
				case 99: //State not desired -> Switch
					$DesiredState = $CurrentState == 1 ? 1 : 0;
					break;
				
				case 0: //Switch to off
					PHUE_SwitchMode($DimmerInstance, 0);
					break;

				case 1: //Switch to on
					PHUE_SwitchMode($DimmerInstance, 1);
					break;

			}
			
			PHUE_SwitchMode($DimmerInstance, 1);
		}

	}
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
			$this -> RegisterPropertyInteger("ChannelBits", 0);
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
		public function SwitchLight($DesiredState)
		{
			$DimmerType = $this->ReadPropertyInteger("DimmerType");
			$DimmerInstance = $this->ReadPropertyInteger("DimmerInstance");
			$DimmerChannel = $this->ReadPropertyInteger("DimmerChannel");
			$ChannelBits = $this->ReadPropertyInteger("ChannelBits");
			$StdPercentage = $this->ReadPropertyInteger("StdPercentage");
			$StdDimTime = $this->ReadPropertyInteger("StdDimTime");
			$StateVariableId = IPS_GetVariableIDByName("State", $DimmerInstance);
			$ChannelSteps = pow(2, $ChannelBits) - 1;
			$CurrentState = GetValue($StateVariableId);
			//Derive SetState
			switch($DesiredState)
			{
				case 99: //State not desired -> Switch
					$SetState = $CurrentState == 1 ? 0 : 1;
					break;
				
				case 0: //Switch to off
					$SetState = 0;
					break;

				case 1: //Switch to on
					$SetState = 1;
					break;

			}

			//Set Light
			switch($DimmerType)
			{
				case 1: if(PHUE_SwitchMode($DimmerInstance, $SetState)) {
					$this->SetValue("Status", $SetState);
					$this->SetValue("Dim", $StdPercentage)};
					break;
				
				case 2: if(DMX_FadeChannel($DimmerInstance, $DimmerChannel, (($ChannelSteps / 100) * $StdPercentage * $SetState), $StdDimTime)) {
					$this->SetValue("Status", $SetState);
					$this->SetValue("Dim", $StdPercentage)};
					break;
			}
			
		}

	}
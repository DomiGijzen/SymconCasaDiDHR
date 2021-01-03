<?php
	class LightControl extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
			
			//Properties
			$this -> RegisterPropertyInteger("InstanceType", 0);
			$this -> RegisterPropertyInteger("Instance", 0);
			$this -> RegisterPropertyInteger("Channel", 0);
			$this -> RegisterPropertyInteger("ChannelBits", 0);
			$this -> RegisterPropertyInteger("StartPercentage", 0);
			$this -> RegisterPropertyInteger("StdPercentage", 50);
			$this -> RegisterPropertyInteger("StdDimTime", 1);

			//StatusVariables
			$this->RegisterVariableBoolean("Status", "Status", "~Switch");
			$this->EnableAction("Status");
			$this->RegisterVariableInteger("Dim", "Dimstand", "DimLedSpot");
			$this->EnableAction("Dim");
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
		public function SwitchLight($DesiredState, $DesiredDim)
		{
			//Get Variables from module
			$InstanceType = $this->ReadPropertyInteger("InstanceType");
			$Instance = $this->ReadPropertyInteger("Instance");
			$Channel = $this->ReadPropertyInteger("Channel");
			$ChannelBits = $this->ReadPropertyInteger("ChannelBits");
			$StdPercentage = $this->ReadPropertyInteger("StdPercentage");
			$StdDimTime = $this->ReadPropertyInteger("StdDimTime");
			$ChannelSteps = pow(2, $ChannelBits) - 1;
			
			//Derive Variables per InstanceType
			switch($InstanceType)
			{
				case 1: //HUE
					$StateVariableId = IPS_GetVariableIDByName("State", $Instance);
					$CurrentState = GetValue($StateVariableId);
					break;
				
				case 2: //DMX
					$StateVariableName = "Channel (".$Channel.")";
					$StateVariableId = IPS_GetVariableIDByName($StateVariableName, $Instance);
					$CurrentState = (GetValue($StateVariableId) != 0) ? 1 : 0;
					break;

				case 3: //IO-Relais
					$StateVariableName = "FB_DO_SW_".sprintf('%03d', $Channel);
					$StateVariableId = IPS_GetVariableIDByName($StateVariableName, $Instance);
					$CurrentState = GetValue($StateVariableId);
			}
			
			//Derive desired state
			if ($DesiredDim == 0) {
				$SetState = 0;
			} else {
				switch($DesiredState)
				{
					case 999: //State not desired -> Switch
						$SetState = $CurrentState == 1 ? 0 : 1;
						break;
					
					case 0: //Switch to off
						$SetState = 0;
						break;

					case 1: //Switch to on
						$SetState = 1;
						break;
				}
			}

			//Derive desired dim
			if ($SetState == 0) {
				$SetDim = 0;
			} 
			elseif ($SetState == 1 & $DesiredDim == 999) {
				$SetDim = $StdPercentage;
			}
			else {
				$SetDim = $DesiredDim;
			}

			//Set Light
			switch($InstanceType)
			{
				case 1: if(PHUE_SwitchMode($Instance, $SetState)) { //HUE
					$this->SetValue("Status", $SetState);
					$this->SetValue("Dim", $SetDim);
					}
					break;
				
				case 2: if(DMX_FadeChannel($Instance, $Channel, (($ChannelSteps / 100) * $SetDim * $SetState), $StdDimTime)) { //DMX
					$this->SetValue("Status", $SetState);
					$this->SetValue("Dim", $SetDim);
					}
					break;

				case 3: //IO-Relais
					$MQTTPayload = ($SetState == 1 ? "TRUE" : "FALSE");
					MQTTC_Publish(27748, "WAGO-PFC200/In/DigitalOutputs/".$StateVariableName, $MQTTPayload, 0, 0);
					sleep(0.2);
					if  (GetValue($StateVariableId) == $SetState) {
					$this->SetValue("Status", $SetState);
					}
			}
			
		}

		public function RequestAction($Ident, $Value)
		{
			if ($Ident === 'Status') {
				$this->SwitchLight($Value, 999);
			}
			if ($Ident === 'Dim') {
				$this->SwitchLight(1, $Value);
			}
		}

	}
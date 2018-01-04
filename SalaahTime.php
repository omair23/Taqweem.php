<?php 

class SalaahTime
{
	    var $Karachi    = 1;    // University of Islamic Sciences, Karachi
		var $MWL        = 2;    // Muslim World League (MWL)
		var $ISNA       = 3;    // Islamic Society of North America (ISNA)
		var $Makkah     = 4;    // Umm al-Qura, Makkah
		var $Egypt      = 5;    // Egyptian General Authority of Survey
		public $FajrAngle = 0;
		public $IshaAngle = 0;
		public $DECLINATION = 0;
		public $EQUATIONTIME = 0;
		public $Latitude = 0 ;
		public $Longitude = 0;
		public $TimeZone = 0;
		public $Height = 0 ;
		public $Jurist = 0;
		public $daynum =0 ;
		public $Date;
		public $Azimuth = 0;	
		public $Azimuth2 = 0;
		public $Azimuth3 = 0;
		public $Zawaal = 0 ;
		public $Sunrise = 0;
		public $Sunset = 0;	
		public $Fajr = 0 ;
		public $Asar1 = 0;
		public $Asar2 = 0;
		public $Maghrib = 0;
		public $Pi;
		
	    function SalaahTime()
    {
    }
	
	function SetData($dayNumber, $paramdate, $latitude, $longitude, $timeZone, $Height, $JuristicMethod){
		$this->Pi = 4 * atan(1);
		$date = @getdate($paramdate);
		$glo_latitude = $latitude;
		$this->Latitude = $latitude * ($this->Pi/180);
		if ($latitude  < 0){
    		$latitude  = -1 * $latitude * ($this->Pi/180) ;
			$this->Latitude = $latitude;
			}else { $latitude = 	$latitude * ($this->Pi/180) ; } 		
		$this->Longitude = $longitude;
		$this->TimeZone = $timeZone ;
		$this->Height = $Height ;
		$this->Jurist = $JuristicMethod;
		$this->daynum =$dayNumber ;
		$this->Date = $date;
		
		if($this->Jurist == $this->Karachi){
			$this->FajrAngle = 18 * $this->Pi / 180;
			$this->IshaAngle = 18 * $this->Pi / 180;}
		elseif($this->Jurist == $this->MWL){
			$this->FajrAngle = 18 * $this->Pi / 180;
			$this->IshaAngle = 17 * $this->Pi / 180; }
		elseif($this->Jurist == $this->ISNA){
			$this->FajrAngle = 15  * $this->Pi / 180;
			$this->IshaAngle = 15 * $this->Pi / 180; }
		elseif($this->Jurist == $this->Makkah ){
			$this->FajrAngle = 18.5 * $this->Pi / 180;
			$this->IshaAngle = 0 * $this->Pi / 180; }
		elseif($this->Jurist == $this->Egypt ){
			$this->FajrAngle =  19.5 * $this->Pi / 180;
			$this->IshaAngle =  17.5 * $this->Pi / 180; }
		else{
			$this->FajrAngle = 18 * $this->Pi / 180 ;
			$this->IshaAngle = 18 * $this->Pi / 180;}
		
		$LOOP_MONTH      = $date['mon'];
    	$LOOP_DAY        = $date['mday'];
    	$LOOP_YEAR       =  $date['year'];
		if ($LOOP_MONTH  > 2){
       		$LOOP_MONTH = $LOOP_MONTH - 3;}
		else{
       		$LOOP_YEAR  = $LOOP_YEAR - 1;
       		$LOOP_MONTH = $LOOP_MONTH + 9 ;}

	$CALC_1  = ($LOOP_DAY + floor(30.6 * $LOOP_MONTH + 0.5) + floor(365.25 * ($LOOP_YEAR - 1976)) - 8707.5) / 36525;
    $CALC_2  = 23.4393 - (0.013 * $CALC_1) ;
    $CALC_3  = 357.528 + (35999.05 * $CALC_1);
    $CALC_3  = $CALC_3 - (360 * floor($CALC_3 / 360));
    $CALC_4  = (1.915 * sin($CALC_3 * $this->Pi / 180)) + (0.02 * sin(2 * $CALC_3 * $this->Pi / 180));
    $CALC_5  = 280.46 + (36000.77 * $CALC_1) + $CALC_4;
    $CALC_5  = $CALC_5 - (360 * floor($CALC_5 / 360));
    $CALC_6  = $CALC_5 - (2.466 * sin(2 * $CALC_5 * $this->Pi / 180)) + (0.053 * sin(4 * $CALC_5 * $this->Pi / 180));
    $DECLINATION = atan(tan($CALC_2 * $this->Pi / 180) * sin($CALC_6 * $this->Pi / 180));
	$this->EQUATIONTIME  = (($CALC_5 - $CALC_4 - $CALC_6) / 15);
	
   if($glo_latitude < 0){
        $DECLINATION = $DECLINATION * -1 ;}
	$this->DECLINATION = $DECLINATION;

	$AZIMUTH = ((sin((-0.8333 * $this->Pi / 180) - (0.0347 * sqrt($Height) * $this->Pi / 180)) - (sin($DECLINATION) * sin($latitude))) / (cos($DECLINATION) * cos($latitude)));
    $AZIMUTH = atan((-1 * $AZIMUTH) / sqrt((-1 * $AZIMUTH) * $AZIMUTH + 1)) + ($this->Pi / 2);
    $AZIMUTH = (180 / (15 * $this->Pi)) * $AZIMUTH	;
	$this->Azimuth = $AZIMUTH;
	
	$AZIMUTH2       =  (((-1) * sin($this->FajrAngle )) - ((sin($latitude) * (sin($DECLINATION))))) / (cos($latitude) * cos($DECLINATION)) ; 
    $AZIMUTH2       =  (acos($AZIMUTH2 ) * (180/$this->Pi)) / 15 ;
	$this->Azimuth2 = $AZIMUTH2;

	$AZIMUTH3       =  (((-1) * sin($this->IshaAngle )) - ((sin($latitude) * (sin($DECLINATION))))) / (cos($latitude) * cos($DECLINATION)) ; 
    $AZIMUTH3       =  (acos($AZIMUTH3 ) * (180/$this->Pi)) / 15 ;
	$this->Azimuth3 = $AZIMUTH3;
	
	$CALC_ASAR1     =  (atan(1 / (1 + tan(($latitude) - ($DECLINATION))))) * (180/$this->Pi);
    $CALC_ASAR1     =  (sin($CALC_ASAR1 * ($this->Pi/180))) - (sin($latitude) * sin($DECLINATION));
    $CALC_ASAR1     =  $CALC_ASAR1 / (cos($latitude) * cos($DECLINATION));
    $CALC_ASAR1     =  (acos($CALC_ASAR1) * (180/$this->Pi)) /15;
	$this->Asar1 = $CALC_ASAR1;
	
	$CALC_ASAR2     =  (atan(1 / (2 + tan(($latitude) - ($DECLINATION))))) * (180/$this->Pi);
    $CALC_ASAR2     =  (sin($CALC_ASAR2 * ($this->Pi/180))) - (sin($latitude) * sin($DECLINATION));
    $CALC_ASAR2     =  $CALC_ASAR2 / (cos($latitude) * cos($DECLINATION));
    $CALC_ASAR2     =  (acos($CALC_ASAR2) * (180/$this->Pi)) /15;
	$this->Asar2 = $CALC_ASAR2;
	}
	
function CalcZawaal(){
	$Test = (12 + $this->TimeZone) - ($this->Longitude / 15) - $this->EQUATIONTIME + 0.00833333 ;
	$this->Zawaal = $Test;
	return $this->floatToTime24($Test);
}

function CalcDhuhr(){
	$Test = $this->Zawaal + 0.05;
	return $this->floatToTime24($Test);
}

function CalcSunrise(){
	$Test = $this->Zawaal - $this->Azimuth;
	$this->Sunrise = $Test;
	return $this->floatToTime24($Test);
}

function CalcSunset(){
	$Test = $this->Zawaal + $this->Azimuth;
	$this->Sunset = $Test;
	return $this->floatToTime24($Test);
}
function CalcMaghrib(){
	$Test = $this->Sunset + 0.05;
	$this->Maghrib = $Test;
	return $this->floatToTime24($Test);
}

function CalcIshraaq(){
	$Test = $this->Sunrise + 0.08333333;
	return $this->floatToTime24($Test);
}

function CalcFajr(){
	$Test = $this->Zawaal - $this->Azimuth2;
	$this->Fajr = $Test;
	return $this->floatToTime24($Test);
}

function CalcIsha(){
	if($this->Jurist == $this->Makkah ){
		$Test = $this->Maghrib + 1.5; 
		return $this->floatToTime24($Test) ; }
	else { 
	$Test = $this->Zawaal + $this->Azimuth3;
	return $this->floatToTime24($Test); }
}

function CalcAsar1(){
	$Test = $this->Zawaal + $this->Asar1;
	return $this->floatToTime24($Test);
}

function CalcAsar2(){
	$Test = $this->Zawaal + $this->Asar2;
	return $this->floatToTime24($Test);
}

function CalcSehriEnds(){
	$this->CalcFajr();
	$Test = $this->Fajr - 0.0833333;
	return $this->floatToTime24($Test);
}
	
function floatToTime24($time)
    {
        if (is_nan($time))
            return '00:00';
        $hours = floor($time);
        $minutes = floor(($time- $hours)* 60);
        return $this->twoDigitsFormat($hours). ':'. $this->twoDigitsFormat($minutes);
    }
	
    function twoDigitsFormat($num)
    {
        return ($num <10) ? '0'. $num : $num;
    }	

}

$salaahTimes = new SalaahTime();

?>
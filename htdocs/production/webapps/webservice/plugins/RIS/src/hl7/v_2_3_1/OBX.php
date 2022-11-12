<?php
namespace RIS\hl7\v_2_3_1;

use RIS\hl7\Segment;

/**
 * Observation/Result (OBX)
 * @author hariansy4h@gmail.com
 */
 
class OBX extends Segment {
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Message Header", "OBX", "ST", 3, "", Field::OPT_R)
			, new Field(1, "SetID", "Set ID", "-", "", "SI", 4, "00569", Field::OPT_O)
			, new Field(2, "ValueType", "Value Type", "-", "", "ID", 3, "00570", Field::OPT_C, "0125")
			, new Field(3, "ObservationIdentifier", "Observation Identifier", "-", "", "CE", 80, "00571", Field::OPT_R)
			, new Field(4, "ObservationSubID", "Observation Sub-ID", "-", "", "ST", 20, "00572", Field::OPT_C)
			, new Field(5, "ObservationValue", "Observation Value", "-", "", "*", 655363, "00573", Field::OPT_C)
			, new Field(6, "Units", "Units", "-", "", "CE", 60, "00574")
			, new Field(7, "ReferencesRange", "References Range", "-", "", "ST", 60, "00575")
			, new Field(8, "AbnormalFlags", "Abnormal Flags", "-", "", "ID", 5, "00576", Field::OPT_O, "0078")
			, new Field(9, "Probability", "Probability", "-", "", "NM", 5, "00577")
			, new Field(10, "NatureOfAbnormalTest", "Nature of Abnormal Test", "-", "", "ID", 2, "00578", Field::OPT_O, "0080")
			, new Field(11, "ObserveResultStatus", "Observe Result Status", "-", "", "ID", 1, "00579", Field::OPT_R, "0085")
			, new Field(12, "DateLastObsNormalValues", "Date Last Obs Normal Values", "-", "", "TS", 26, "00580")
			, new Field(13, "UserDefinedAccessChecks", "User Defined Access Checks", "-", "", "ST", 20, "00581")
			, new Field(14, "DateOfTheObservation", "Date/Time of the Observation", "-", "", "TS", 26, "00582")
			, new Field(15, "ProducerID", "Producer's ID", "-", "", "CE", 60, "00583")
			, new Field(16, "ResponsibleObserver", "Responsible Observer", "-", "", "XCN", 80, "00584")
			, new Field(17, "ObservationMethod", "Observation Method", "-", "", "CE", 60, "00936")
		];
	}
}
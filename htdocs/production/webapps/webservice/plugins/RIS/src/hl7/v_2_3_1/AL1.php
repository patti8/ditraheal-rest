<?php
namespace RIS\hl7\v_2_3_1;

use RIS\hl7\Segment;

/**
 * Allergy Information (AL1)
 * @author hariansy4h@gmail.com
 */
 
class AL1 extends Segment {
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Message Header", "AL1", "ST", 3, "", Field::OPT_R)
			, new Field(1, "SetID", "Set ID", "-", "", "SI", 4, "00203", Field::OPT_R)
			, new Field(2, "AllergyType", "Allergy Type", "-", "", "IS", 2, "00204", Field::OPT_O, "0127")
			, new Field(3, "AllergyCode", "Allergy Code/Mnemonic/Description", "-", "", "CE", 60, "00205", Field::OPT_R)
			, new Field(4, "AllergySeverity", "Allergy Severity", "-", "", "IS", 2, "00206", Field::OPT_O, "0128")
			, new Field(5, "AllergyReaction", "Allergy Reaction", "-", "", "ST", 15, "00207")
			, new Field(6, "IdentificationDate", "Identification Date", "-", "", "DT", 8, "00208")			
		];
	}
}
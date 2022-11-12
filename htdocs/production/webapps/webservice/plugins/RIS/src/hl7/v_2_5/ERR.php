<?php
namespace RIS\hl7\v_2_5;

use RIS\hl7\Segment;
/**
 * Error Segment (ERR)
 * @author hariansy4h@gmail.com
 */
 
class ERR extends Segment {
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Error Segment", "ERR", "ST", 3, "", Field::OPT_R)
			, new Field(1, "ErrorCodeAndLocation", "Error code and location", "-", "", "ELD", 493, "00024", Field::OPT_X, "0..0")
			, new Field(2, "ErrorLocation", "Error Location", "-", "", "ERL", 18, "01812", Field::OPT_RE, "0..*")
			, new Field(3, "HL7ErrorCode", "HL7 Error Code", "-", "", "CWE", 705, "01813", Field::OPT_R, "1..1", "0357")
			, new Field(4, "Severity", "Severity", "-", "", "ID", 2, "01814", Field::OPT_R, "1..1", "0516")
			, new Field(5, "ApplicationErrorCode", "Application Error Code", "-", "", "CWE", 705, "01815", Field::OPT_O, "0..1", "0533")
			, new Field(6, "ApplicationErrorParameter", "Application Error Parameter", "-", "", "ST", 80, "01816", Field::OPT_O, "0..10")
			, new Field(7, "DiagnosticInformation", "Diagnostic Information", "-", "", "TX", 2048, "01817", Field::OPT_O, "0..1")
			, new Field(8, "UserMessage", "User Message", "-", "", "TX", 250, "01818", Field::OPT_O, "0..1")
			, new Field(9, "InformPersonIndicator", "Inform Person Indicator", "-", "", "IS", 20, "01819", Field::OPT_O, "0..*", "0517")
			, new Field(10, "OverrideType", "Override Type", "-", "", "CWE", 705, "01820", Field::OPT_O, "0..1", "0518")
			, new Field(11, "OverrideReasonCode", "Override Reason Code", "-", "", "CWE", 705, "01821", Field::OPT_O, "0..*", "0519")
			, new Field(12, "HelpDeskContactPoint", "Help Desk Contact Point", "-", "", "XTN", 652, "01822", Field::OPT_O, "0..*")
		];
	}
}
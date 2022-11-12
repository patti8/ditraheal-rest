<?php
namespace RIS\hl7\v_2_3_1;

use RIS\hl7\Segment;
/**
 * Common Order (ORC)
 * @author hariansy4h@gmail.com
 * @see https://hl7-definition.caristix.com/v2/HL7v2.8/Tables/0119
 */
 
class ORC extends Segment {
	/* Order Control Code 
	 */	 
	const OC_NEW_ORDER = "NW";
	const OC_PARENT_ORDER = "PA";
	const OC_CHILD_ORDER = "CH";
	const OC_CANCEL_ORDER = "CA";
	
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Message Header", "ORC", "ST", 3, "", Field::OPT_R)
			, new Field(1, "OrderControl", "Order Control"
				, "Order Control Code: ".
				  " NW = New Order | Required".
				  " PA = Parent Order | Optional".
				  " CH = Child Order | Optional".
				  " CA = Cancel Order | Optional"
				, ORC::OC_NEW_ORDER, "ID", 2, "00215", Field::OPT_R, "0119")
			, new Field(2, "PlacerOrderNumber", "Placer Order Number", "-", "", "EI", 22, "00216", Field::OPT_R)
			, new Field(3, "FillerOrderNumber", "Filler Order Number", "-", "", "EI", 22, "00217")
			, new Field(4, "PlacerGroupNumber", "Placer Group Number", "-", "", "EI", 22, "00218", Field::OPT_C)
			, new Field(5, "OrderStatus", "Order Status", "-", "", "ID", 2, "00219", Field::OPT_O, "0038")
			, new Field(6, "ResponseFlag", "Response Flag", "-", "", "ID", 1, "00220", Field::OPT_O, "0121")
			, new Field(7, "Quantity", "Quantity/Timing", "-", "", "TQ", 200, "00221", Field::OPT_R)
			, new Field(8, "Parent", "Parent", "-", "", "CM", 200, "00222", Field::OPT_C)
			, new Field(9, "DateOfTransaction", "Date/Time of Transaction", "-", "", "TS", 26, "00223", Field::OPT_R)
			, new Field(10, "EnteredBy", "Entered By", "-", "", "XCN", 120, "00224", Field::OPT_RE)
			, new Field(11, "VerifiedBy", "Verified By", "-", "", "XCN", 120, "00225")
			, new Field(12, "OrderingProvider", "Ordering Provider", "-", "", "XCN", 120, "00226", Field::OPT_R)
			, new Field(13, "EntererLocation", "Enterer's Location", "-", "", "PL", 80, "00227")
			, new Field(14, "CallBackPhoneNumber", "Call Back Phone Number", "-", "", "XTN", 40, "00228", Field::OPT_RE)
			, new Field(15, "OrderEffectiveDate", "Order Effective Date/Time", "-", "", "TS", 26, "00229")
			, new Field(16, "OrderControlCodeReason", "Order Control Code Reason", "-", "", "CE", 200, "00230")
			, new Field(17, "EnteringOrganization", "Entering Organization", "-", "", "CE", 60, "00231", Field::OPT_R)
			, new Field(18, "EnteringDevice", "Entering Device", "-", "", "CE", 60, "00232")
			, new Field(19, "ActionBy", "Action By", "-", "", "XCN", 120, "00233")
		];
	}
}
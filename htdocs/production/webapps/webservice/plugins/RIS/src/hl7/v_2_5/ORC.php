<?php
namespace RIS\hl7\v_2_5;

use RIS\hl7\Segment;
/**
 * Common Order (ORC)
 * @author hariansy4h@gmail.com
 */
 
class ORC extends Segment {
	/* Order Control Code 
	 */	 
	const OC_NEW_ORDER = "NW";
	const OC_PARENT_ORDER = "PA";
	const OC_CHILD_ORDER = "CH";
	const OC_CHANGE_ORDER = "XO";
	const OC_CANCEL_ORDER = "CA";
	
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Message Header", "ORC", "ST", 3, "", Field::OPT_R)
			, new Field(1, "OrderControl", "Order Control"
				, "Order Control Code: ".
				  " NW = New Order | Required".
				  " PA = Parent Order | Optional".
				  " CH = Child Order | Optional ".
				  " XO = Change Order | Required".
				  " CA = Cancel Order | Optional"
				, ORC::OC_NEW_ORDER, "ID", 2, "00215", Field::OPT_R, "1..1", "0119")
			, new Field(2, "PlacerOrderNumber", "Placer Order Number", "-", "", "EI", 22, "00216", Field::OPT_R)
			, new Field(3, "FillerOrderNumber", "Filler Order Number", "-", "", "EI", 22, "00217", Field::OPT_X)
			, new Field(4, "PlacerGroupNumber", "Placer Group Number", "-", "", "EI", 22, "00218", Field::OPT_C)
			, new Field(5, "OrderStatus", "Order Status", "-", "", "ID", 2, "00219", Field::OPT_O, "0..1", "0038")
			, new Field(6, "ResponseFlag", "Response Flag", "-", "", "ID", 1, "00220", Field::OPT_O, "0..1", "0121")
			, new Field(7, "Quantity", "Quantity/Timing", "-", "", "TQ", 200, "00221", Field::OPT_X)
			, new Field(8, "Parent", "Parent", "-", "", "EIP", 200, "00222", Field::OPT_C)
			, new Field(9, "DateOfTransaction", "Date/Time of Transaction", "-", "", "TS", 26, "00223", Field::OPT_R)
			, new Field(10, "EnteredBy", "Entered By", "-", "", "XCN", 250, "00224", Field::OPT_RE)
			, new Field(11, "VerifiedBy", "Verified By", "-", "", "XCN", 250, "00225")
			, new Field(12, "OrderingProvider", "Ordering Provider", "-", "", "XCN", 250, "00226", Field::OPT_R)
			, new Field(13, "EntererLocation", "Enterer's Location", "-", "", "PL", 80, "00227")
			, new Field(14, "CallBackPhoneNumber", "Call Back Phone Number", "-", "", "XTN", 250, "00228", Field::OPT_RE)
			, new Field(15, "OrderEffectiveDate", "Order Effective Date/Time", "-", "", "TS", 26, "00229")
			, new Field(16, "OrderControlCodeReason", "Order Control Code Reason", "-", "", "CE", 250, "00230")
			, new Field(17, "EnteringOrganization", "Entering Organization", "-", "", "CE", 250, "00231", Field::OPT_R)
			, new Field(18, "EnteringDevice", "Entering Device", "-", "", "CE", 250, "00232")
			, new Field(19, "ActionBy", "Action By", "-", "", "XCN", 250, "00233")
			, new Field(20, "AdvancedBeneficiaryNoticeCode", "Advanced Beneficiary Notice Code", "-", "", "CE", 250, "01310", Field::OPT_O, "0..1", "0339")
			, new Field(21, "OrderingFacilityName", "Ordering Facility Name", "-", "", "XON", 250, "01311")
			, new Field(22, "OrderingFacilityAddress", "Ordering Facility Address", "-", "", "XAD", 250, "01312")
			, new Field(23, "OrderingFacilityPhoneNumber", "Ordering Facility Phone Number", "-", "", "XTN", 250, "01313")
			, new Field(24, "OrderingProviderAddress", "Ordering Provider Address", "-", "", "XAD", 250, "01314")
			, new Field(25, "OrderStatusModifier", "Order Status Modifier", "-", "", "CWE", 250, "01473")
			, new Field(26, "AdvancedBeneficiaryNoticeOverrideReason", "Advanced Beneficiary Notice Override Reason", "-", "", "CWE", 60, "01641", Field::OPT_C, "1..1", "0552")
			, new Field(27, "FillerExpectedAvailabilityDate", "Filler’s Expected Availability Date/Time", "-", "", "TS", 26, "01642")
			, new Field(28, "ConfidentialityCode", "Confidentiality Code", "-", "", "CWE", 250, "00615", Field::OPT_O, "0..1", "0177")
			, new Field(29, "OrderType", "Order Type", "-", "", "CWE", 250, "01643", Field::OPT_O, "0..1", "0482")
			, new Field(30, "EntererAuthorizationMode", "Enterer Authorization Mode", "-", "", "CNE", 250, "01644", Field::OPT_O, "0..1", "0483")
			, new Field(31, "ParentUniversalServiceIdentifier", "Parent Universal Service Identifier", "-", "", "CWE", 250, "02286")
		];
	}
}
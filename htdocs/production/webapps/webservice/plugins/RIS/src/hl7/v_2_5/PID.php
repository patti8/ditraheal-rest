<?php
namespace RIS\hl7\v_2_5;

use RIS\hl7\Segment;
/**
 * Patient Identification (PID)
 * @author hariansy4h@gmail.com
 */
 
class PID extends Segment {
	protected function initFields() {
		$this->fields = [
			new Field(0, "SegmentIdentifier", "Segment Identifier", "Message Header", "PID", "ST", 3, "", Field::OPT_R)
			, new Field(1, "SetID", "Set ID - Patient ID", "-", "", "SI", 4, "00104")
			, new Field(2, "PatientID", "Patient ID", "-", "", "CX", 20, "00105")
			, new Field(3, "PatientIdentifierList", "Patient Identifier List", "-", "", "CX", 250, "00106", Field::OPT_R)
			, new Field(4, "AlternatePatientID", "Alternate Patient ID", "-", "", "CX", 20, "00107")
			, new Field(5, "PatientName", "Patient Name", "-", "", "XPN", 250, "00108", Field::OPT_R)
			, new Field(6, "MotherMaidenName", "Mother's Maiden Name", "-", "", "XPN", 48, "00109")
			, new Field(7, "DateOfBirth", "Date/Time of Birth", "-", "", "TS", 26, "00110", Field::OPT_RE)
			, new Field(8, "Sex", "Administrative Sex", "-", "", "IS", 1, "00111", Field::OPT_R, "1..1", "0001")
			, new Field(9, "PatientAlias", "Patient Alias", "-", "", "XPN", 48, "00112")
			, new Field(10, "Race", "Race", "-", "", "CE", 250, "00113", Field::OPT_RE, "1..1", "0005")
			, new Field(11, "PatientAddress", "Patient Address", "-", "", "XAD", 250, "00114", Field::OPT_RE)
			, new Field(12, "CountyCode", "County Code", "-", "", "IS", 4, "00115")
			, new Field(13, "PhoneNumberHome", "Phone Number - Home", "-", "", "XTN", 40, "00116")
			, new Field(14, "PhoneNumberBusiness", "Phone Number - Business", "-", "", "XTN", 40, "00117")
			, new Field(15, "PrimaryLanguage", "Primary Language", "-", "", "CE", 60, "00118", Field::OPT_O, "0..1", "0296")
			, new Field(16, "MaritalStatus", "Marital Status", "-", "", "IS", 1, "00119", Field::OPT_O, "0..1", "0002")
			, new Field(17, "Religion", "Religion", "-", "", "CE", 80, "00120", Field::OPT_O, "0..1", "0006")
			, new Field(18, "PatientAccountNumber", "Patient Account Number", "-", "", "CX", 250, "00121", Field::OPT_C)
			, new Field(19, "SSNumber", "SSN Number – Patient", "-", "", "ST", 16, "00122")
			, new Field(20, "DriverLicenseNumber", "Driver's License Number - Patient", "-", "", "DLN", 25, "00123")
			, new Field(21, "MotherIdentifier", "Mother's Identifier", "-", "", "CX", 20, "00124")
			, new Field(22, "EthnicGroup", "Ethnic Group", "-", "", "CE", 80, "00125", Field::OPT_O, "0..1", "0189")
			, new Field(23, "BirthPlace", "Birth Place", "-", "", "ST", 60, "00126")
			, new Field(24, "MultipleBirthIndicator", "Multiple Birth Indicator", "-", "", "ID", 1, "00127", Field::OPT_O, "0..1", "0136")
			, new Field(25, "BirthOrder", "Birth Order", "-", "", "NM", 2, "00128")
			, new Field(26, "Citizenship", "Citizenship", "-", "", "CE", 80, "00129", Field::OPT_O, "0..1", "0171")
			, new Field(27, "VeteransMilitaryStatus", "Veterans Military Status", "-", "", "CE", 60, "00130", Field::OPT_O, "0..1", "0172")
			, new Field(28, "Nationality", "Nationality", "-", "", "CE", 80, "00739")
			, new Field(29, "PatientDeathDateAndTime", "Patient Death Date and Time", "-", "", "TS", 26, "00740")
			, new Field(30, "PatientDeathIndicator", "Patient Death Indicator", "-", "", "ID", 1, "00741", Field::OPT_O, "0..1", "0136")
		];
	}
}
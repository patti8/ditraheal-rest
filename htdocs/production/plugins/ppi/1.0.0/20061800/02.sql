USE `master`;

REPLACE INTO `jenis_referensi` (`ID`, `DESKRIPSI`, `SINGKATAN`, `APLIKASI`) VALUES (128, 'Antibiotik', '', 0);
REPLACE INTO `jenis_referensi` (`ID`, `DESKRIPSI`, `SINGKATAN`, `APLIKASI`) VALUES (152, 'Jenis Pemeriksaan Kultur', '', 0);

REPLACE INTO `referensi` (`JENIS`, `ID`, `DESKRIPSI`, `REF_ID`, `STATUS`) VALUES (128, 1, 'Cefoxitin Screen', '', 1);
REPLACE INTO `referensi` (`JENIS`, `ID`, `DESKRIPSI`, `REF_ID`, `STATUS`) VALUES (128, 2, 'Amoxicilin', '', 1);
REPLACE INTO `referensi` (`JENIS`, `ID`, `DESKRIPSI`, `REF_ID`, `STATUS`) VALUES (128, 3, 'Ciprofloxacin', '', 1);
REPLACE INTO `referensi` (`JENIS`, `ID`, `DESKRIPSI`, `REF_ID`, `STATUS`) VALUES (128, 4, 'Tetrasiklin', '', 1);

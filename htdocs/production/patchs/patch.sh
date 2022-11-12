#!/bin/sh

if [ $# -lt 1 ]; then
	echo "Usage: $0 last_version"
	echo "Example: bash patch.sh 2.0-190929"
	exit 1
fi

version="$1"

if [ "$version" == "" ]; then	
	if [ -f /var/log/simrsgos-latest.txt ]; then
		version=`cat /var/log/simrsgos-latest.txt`
	fi	
fi

if [ "$version" == "test" ]; then
	if [ -f /var/log/simrsgos-test.txt ]; then
		version=`cat /var/log/simrsgos-test.txt`
	else
		version="";
	fi
fi

if [ "$version" == "" ]; then
	version="2.0-190929"
fi

echo "Latest Version: $version"

lastVersion=`echo $version | cut -d \- -f 1`
lastRelease=`echo $version | cut -d \- -f 2`

cat > /var/tmp/my.cnf <<-EOF
[client]
host=127.0.0.1
port=3306
user=root
password=S!MRSGos2
EOF

versionlatest=""
patchlatest=""

vers=`ls -d */`
for ver in $vers
do
	v=${ver/./}
	v=${v/./}
	v=${v///}
	versionlatest="${ver///}"
	lastVersion=${lastVersion/./}
	lastVersion=${lastVersion/./}
	patchlatest=$lastRelease
	if [ "$v" -ge "$lastVersion" ]; then
		echo "========================================"
		echo "Version: ${ver///}"
		echo "========================================"
		cd $ver
		patchs=`ls -d */`
		for patch in $patchs
		do
			patchlatest="${patch///}"
			if [ "${patch///}" -gt "$lastRelease" ]; then
				echo "## Patch: ${patch///}"
				cd $patch
				sqls=`ls`
				for sql in $sqls
				do
					echo "--------> SQL: ${sql///}"
					mysql --defaults-extra-file=/var/tmp/my.cnf < $sql
				done
				cd ..
			fi
		done
		echo ""
		echo ""
		cd ..	
	fi
done

cat > /var/log/simrsgos-test.txt <<-EOF
$versionlatest-$patchlatest
EOF

rm -Rf /var/tmp/my.cnf

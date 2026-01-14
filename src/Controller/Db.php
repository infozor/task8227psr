<?php

namespace Ion\Task8227psr\Controller;

class Db
{
	private $data_bases;
	private $config;
	private $conn; // bollard
	private $conn2; // ViewerClaim
	private $conn3; // backoffice
	function __construct()
	{
		$Config = new Config();
		$this->config = $Config->get_data();

		$db_servername = $this->data_bases = $this->config['data_bases']['db1']['db_servername'];
		$db_username = $this->data_bases = $this->config['data_bases']['db1']['db_username'];
		$db_password = $this->data_bases = $this->config['data_bases']['db1']['db_password'];
		$db_name = $this->data_bases = $this->config['data_bases']['db1']['db_name'];
		$dbPort = $this->data_bases = $this->config['data_bases']['db1']['dbPort'];

		$conn = new \PDO("pgsql:host=$db_servername;port=$dbPort;dbname=$db_name", $db_username, $db_password);
		$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		$db_name = $this->data_bases = $this->config['data_bases']['db2']['db_name'];

		$conn2 = new \PDO("pgsql:host=$db_servername;port=$dbPort;dbname=$db_name", $db_username, $db_password);
		$conn2->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		$db_name = $this->data_bases = $this->config['data_bases']['db3']['db_name'];
		$conn3 = new \PDO("pgsql:host=$db_servername;port=$dbPort;dbname=$db_name", $db_username, $db_password);
		$conn3->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		$this->conn = $conn;
		$this->conn2 = $conn2;
		$this->conn3 = $conn3;
	}
	function getConn()
	{
		return $this->conn;
	}
	function getConn2()
	{
		return $this->conn2;
	}
	function insert_yandex_ad_statistics($params)
	{
		// @params
		$upload_id = $params['upload_id'];
		$stdata = $params['stdata'];
		$poiskovyy_zapros = $params['poiskovyy_zapros'];
		$kampaniya = $params['kampaniya'];
		$n_kampanii = $params['n_kampanii'];
		$gruppa = $params['gruppa'];
		$n_gruppyi = $params['n_gruppyi'];
		$n_obiyavleniya = $params['n_obiyavleniya'];
		$uslovie_pokaza = $params['uslovie_pokaza'];
		$n_usloviya_pokaza = $params['n_usloviya_pokaza'];
		$pokazy = $params['pokazy'];
		$klike = $params['klike'];
		$ctr_percent = $params['ctr_percent'];
		$raskhod_rub = $params['raskhod_rub'];
		$sr_tsena_klika_rub = $params['sr_tsena_klika_rub'];

		$sqlstr = sprintf("
        INSERT INTO
          public.yandex_ad_statistics
        (
          upload_id,
              stdata,
              poiskovyy_zapros,
              kampaniya,
              n_kampanii,
              gruppa,
              n_gruppyi,
              n_obiyavleniya,
              uslovie_pokaza,
              n_usloviya_pokaza,
              pokazy,
              klike,
              ctr_percent,
              raskhod_rub,
              sr_tsena_klika_rub
        )
        VALUES (
          '%s', -- upload_id
              '%s', -- stdata
              '%s', -- poiskovyy_zapros
              '%s', -- kampaniya
              '%s', -- n_kampanii
              '%s', -- gruppa
              '%s', -- n_gruppyi
              '%s', -- n_obiyavleniya
              '%s', -- uslovie_pokaza
              '%s', -- n_usloviya_pokaza
              '%s', -- pokazy
              '%s', -- klike
              '%s', -- ctr_percent
              '%s', -- raskhod_rub
              '%s' -- sr_tsena_klika_rub
        );
        ", $upload_id, $stdata, $poiskovyy_zapros, $kampaniya, $n_kampanii, $gruppa, $n_gruppyi, $n_obiyavleniya, $uslovie_pokaza, $n_usloviya_pokaza, $pokazy, $klike, $ctr_percent, $raskhod_rub, $sr_tsena_klika_rub);

		$stmt = $this->conn->prepare($sqlstr);
		$stmt->execute();

		return 1;
	}
	function insert_yandex_ad_uploads($params)
	{
		// @params
		$vsego_po_poiskovym_zaprosam = $params['vsego_po_poiskovym_zaprosam'];
		$sr_rashody_za_den_rub = $params['sr_rashody_za_den_rub'];
		$pokazy = $params['pokazy'];
		$kliky = $params['kliky'];
		$ctr_protsenty = $params['ctr_protsenty'];
		$rashod_rub = $params['rashod_rub'];
		$sr_tsena_kliка_rub = $params['sr_tsena_kliка_rub'];

		$sqlstr = sprintf("
        INSERT INTO
          public.yandex_ad_uploads
        (
          vsego_po_poiskovym_zaprosam,
              sr_rashody_za_den_rub,
              pokazy,
              kliky,
              ctr_protsenty,
              rashod_rub,
              sr_tsena_kliка_rub,
              created_at
        )
        VALUES (
          '%s', -- vsego_po_poiskovym_zaprosam
              '%s', -- sr_rashody_za_den_rub
              '%s', -- pokazy
              '%s', -- kliky
              '%s', -- ctr_protsenty
              '%s', -- rashod_rub
              '%s', -- sr_tsena_kliка_rub
              NOW()
        );
        ", $vsego_po_poiskovym_zaprosam, $sr_rashody_za_den_rub, $pokazy, $kliky, $ctr_protsenty, $rashod_rub, $sr_tsena_kliка_rub);

		$stmt = $this->conn->prepare($sqlstr);
		$stmt->execute();

		$stmt = $this->conn->prepare("SELECT currval('yandex_ad_uploads_id_seq')");
		$stmt->execute();
		$fetch = $stmt->fetchAll();

		$last_insert_id = $fetch[0]['currval'];
		return $last_insert_id;
	}

	//@todo taskp2554 25.11.2025 12:38
	function get_group($params)
	{
		// @params
		$sqlstr = sprintf("
			SELECT 
			  good_id,
              catnumber,  
			  name,
			  price,
			  SUM(kol) as total_kol,
			  COUNT(*) as record_count
			FROM public.\"mBest4\" 
			WHERE kol > 0 
			  AND price > 5000 
			  AND producer IS NOT NULL
			  AND producer != ''
			  AND name IS NOT NULL
			  AND name != ''
			  AND LEFT(\"group\", 1) <> '2'
			  AND LEFT(\"group\", 2) NOT IN ('31','32','35','36','42','43','4I','4J','4K','4L','41')
			  AND LEFT(\"group\", 3) NOT IN ('198','446','195','45M','45N','47E','4G4','4GD','4GE','4E3')
			  AND LEFT(\"group\", 4) NOT IN ('4G35')
			  AND LEFT(\"group1\", 3) NOT IN ('17F','211','212','183','376','237','238','239')
			  AND profil IS NOT NULL
			  AND profil != ''
			  AND LENGTH(TRIM(catnumber)) > 3
			GROUP BY good_id, name, price
			ORDER BY price DESC, total_kol DESC;
			", null);

		$stmt = $this->conn2->prepare($sqlstr);
		$stmt->execute();
		$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $rows;
	}
	function get_product($params)
	{
		// @params
		$sqlstr = sprintf("
			SELECT
			id,
			error,
			good_id,
			profil,
			catnumber,
			catnumber_clear,
			name,
			alias,
			kol,
			kolsklad,
			price,
			producer,
			analogs,
			brkol,
			brkol2,
			supplier_id,
			days,
			priceopt,
			priceinet,
			priceinet2,
			priceinet3,
			\"group\",
			group1,
			deleted,
			deletedon,
			created_at,
			updated_at,
			mksprstr_id,
			reindex
			FROM
			public.products
			WHERE
			good_id = %s
			", $params['good_id']);

		$stmt = $this->conn3->prepare($sqlstr);
		$stmt->execute();
		$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $rows;
	}
	function getYadFormatTitle()
	{
		$sqlstr = sprintf("
		SELECT
		  \"YaDFormatTitle_ID\",
		  \"Note\",
		  \"LenNote\"
		FROM
		  public.\"YaDFormatTitle\"
			", null);

		$stmt = $this->conn2->prepare($sqlstr);
		$stmt->execute();
		$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $rows;
	}
	function getYadFormaText()
	{
		$sqlstr = sprintf("
			SELECT 
			  \"YaDFormatText_ID\",
			  \"PriceName\",
			  \"Note\",
			  \"LenNote\"
			FROM 
			  public.\"YaDFormatText\"
			", null);

		$stmt = $this->conn2->prepare($sqlstr);
		$stmt->execute();
		$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $rows;
	}
	function insert_yadv_a_packet_products($params)
	{
		// @params
		$yadv_packet_id = $params['yadv_packet_id'];
		$good_id = $params['good_id'];
		$name = $params['name'];
		$short_name = $params['short_name'];

		$sqlstr = sprintf("
        INSERT INTO
          public.yadv_a_packet_products
        (     yadv_packet_id,
              good_id,
              name,
              short_name,
              created_at,
              updated_at
        )
        VALUES (
          '%s', -- yadv_packet_id
              '%s', -- good_id
              '%s', -- name
              '%s', -- short_name
              NOW(), -- created_at
              NOW() -- updated_at
        );
        ", $yadv_packet_id, $good_id, $name, $short_name);

		$stmt = $this->conn->prepare($sqlstr);
		$stmt->execute();
	}
	function get_yadv_a_packet_products($params)
	{
		// @params
		//$id = $params['id'];
		$yadv_packet_id = $params['yadv_packet_id'];

		/*
		 $good_id = $params['good_id'];
		 $name = $params['name'];
		 $short_name = $params['short_name'];
		 $created_at = $params['created_at'];
		 $updated_at = $params['updated_at'];
		 */

		$sqlstr = sprintf("
			SELECT
              id,
			  yadv_packet_id,
			  good_id,
			  name,
			  short_name,
			  created_at,
			  updated_at
				
			FROM
			  public.yadv_a_packet_products
			WHERE
			  yadv_packet_id = %s
			ORDER BY
			  id
            --LIMIT 5
				
			", $yadv_packet_id);

		$stmt = $this->conn->prepare($sqlstr);
		$stmt->execute();
		$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $rows;
	}
	function update_yadv_a_packet_products($params)
	{
		// @params
		/*
		$id = $params['id'];
		$yadv_packet_id = $params['yadv_packet_id'];
		$good_id = $params['good_id'];
		$name = $params['name'];
		$short_name = $params['short_name'];
		$created_at = $params['created_at'];
		$updated_at = $params['updated_at'];
		*/
		
		$id = $params['id'];
		$short_name = $params['short_name'];
		
		$sqlstr = sprintf("
        UPDATE
          public.yadv_a_packet_products
        SET
			  short_name = '%s', --short_name
			  updated_at = NOW() --updated_at
        WHERE
          id = %s
        ;
        ", $short_name, $id);

		$stmt = $this->conn->prepare($sqlstr);
		$stmt->execute();
	}
}

/*

$Test = new Test;

$date = date('d.m.Y');

// @set params
$params['upload_id'] = 1;
$params['stdata'] = $date;
$params['poiskovyy_zapros'] = 'poiskovyy_zapros';
$params['kampaniya'] = 'kampaniya';
$params['n_kampanii'] = 1;
$params['gruppa'] = 'gruppa';
$params['n_gruppyi'] = 1;
$params['uslovie_pokaza'] = 'uslovie_pokaza';
$params['tip_sootvetstviya'] = 'tip_sootvetstviya';
$params['podobranaya_fraza'] = 'podobranaya_fraza';
$params['kategorya_targetinga'] = 'kategorya_targetinga';
$params['pokazy'] = 5;
$params['klike'] = 2;
$params['ctr_percent'] = 38;
$params['raskhod_rub'] = 3;
$params['sr_tsena_klika_rub'] = 2;
$params['konversiya_percent'] = 8;
$params['tsena_tseli_rub'] = 9;
$params['konversii'] = 3;

$rows = $Test->insert_yandex_ad_statistics($params);
*/
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roboter
 * Date: 10.01.13
 * Time: 14:00
 * To change this template use File | Settings | File Templates.
 */

class ModelDesignTemplate extends Model
{

	public function getTemplates($data = array ())
	{
		$sql = "SELECT `template_id`, `name`, `filename` FROM ".DB_PREFIX."template";

		$sort_data = array ('name');

		if (isset($data['sort']) && in_array($data['sort'], $sort_data))
		{
			$sql .= " ORDER BY ".$data['sort'];
		}
		else
		{
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC'))
		{
			$sql .= " DESC";
		}
		else
		{
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit']))
		{
			if ($data['start'] < 0)
			{
				$data['start'] = 0;
			}

			if ($data['limit'] < 1)
			{
				$data['limit'] = 20;
			}

			$sql .= " LIMIT ".(int)$data['start'].",".(int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}


	public function getTotalTemplates()
	{
		$query = $this->db->query("SELECT COUNT(*) AS total FROM ".DB_PREFIX."template");

		return $query->row['total'];
	}

	public function addTemplate($data)
	{
		$this->db->query("INSERT INTO ".DB_PREFIX."template SET name = '".$this->db->escape($data['name'])."', `filename`='".$this->db->escape($data['filename'])."' ");

		$template_id = $this->db->getLastId();

	}

	public function editTemplate($template_id, $data)
	{
		$this->db->query(
			"UPDATE ".DB_PREFIX."template SET name = '".$this->db->escape($data['name'])."', `filename`='".$this->db->escape($data['filename'])."' WHERE template_id = '".
				(int)$template_id."'");

	}

	public function deleteTemplate($template_id)
	{
		$this->db->query("DELETE FROM ".DB_PREFIX."template WHERE template_id = '".(int)$template_id."'");
	}

	public function createTemplateTable()
	{
        $isExists =self::isTableExists(DB_PREFIX."template");
		if ($isExists != 1)
		{
			$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."template` (
	`template_id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(64) NOT NULL COLLATE 'utf8_bin',
	`filename` VARCHAR(64) NOT NULL COLLATE 'utf8_bin',
	PRIMARY KEY (`template_id`)
);");
        }
        if($isExists == 0)
        {
			$this->db->query("REPLACE INTO `".DB_PREFIX.
				"template` (`template_id`, `name`, `filename`) VALUES (1, 'new product', 'newproduct');");
			$this->db->query("REPLACE INTO `".DB_PREFIX.
				"template` (`template_id`, `name`, `filename`) VALUES (2, 'vertical', 'newproduct_vertical');");
		}
	}

	public function getTemplate($template_id)
	{
		$query =
			$this->db->query("SELECT `template_id`,`name`,`filename` FROM ".DB_PREFIX."template WHERE template_id = '".
				(int)$template_id."' LIMIT 1");

		return $query->row;
	}

	private static function isTableExists($table)
	{
		$result = mysql_query("SHOW TABLES FROM ".DB_DATABASE);

        if($result)
        {
            while ($row = mysql_fetch_row($result))
            {
                $arr[] = $row[0];
            }

            return in_array($table, $arr)? 1 : 0;
        }
        return 2; // No permission to check
	}
}
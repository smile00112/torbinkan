<?php


class ModelDesignTemplate extends Model
{
	public function getTemplate($template_id)
	{
		$query =
			$this->db->query("SELECT `filename` FROM `".DB_PREFIX."template` WHERE `template_id`=".(int)$template_id." LIMIT 1");

		if ($query->num_rows)
			return $query->row;


	}
}
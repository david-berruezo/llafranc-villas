<?php

class DynamicModel
{
	protected $DBGroup              = 'default';
	protected $table                = 'dynamics';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];


    /**
     * @param $slug
     * @param $idioma
     * @param int $id
     * @param null $order
     * @return array
     */
    public function get_dynamic_table_data($slug, $idioma, $id = 0, $order = null) {

        $table = 'dynamic_' . $slug;

        if (!$this->table_exists($table))
            return array();

        if ($id)
            $this->db->where('id', $id);

        $this->db->where('language', $idioma);

        if (!is_null($order))
            $this->db->order_by($order, 'ASC');

        $query = $this->db->get($table);
        return ($id) ? $query->row() : $query->result();

    }// end function


    /**
     * @param $slug
     * @param $idioma
     * @param int $id
     * @param null $order
     * @return array
     */
    public function get_dynamic_table_data_desc($slug, $idioma, $id = 0, $order = null) {

        $table = 'dynamic_' . $slug;

        if (!$this->table_exists($table))
            return array();
        if ($id)
            $this->db->where('id', $id);
        $this->db->where('language', $idioma);
        if (!is_null($order))
            $this->db->order_by($order, 'DESC');
        $query = $this->db->get($table);

        return ($id) ? $query->row() : $query->result();

    }// end function



    public function get_related_table_data($slug, $tables, $id) {

        $aRet = array();
        $table_related = 'related_' . $slug;
        $table_info = $tables[0]['table'];
        $table_select = $tables[1]['table'];

        if (!$this->table_exists($table_related))
            return $aRet;
        if (!$this->table_exists($table_info))
            return $aRet;
        if (!$this->table_exists($table_select))
            return $aRet;

        # Información de las relaciones:
        $aRet['selected'] = array();
        $query = $this->db->query("SELECT * FROM " . $table_related . " WHERE " . $table_info . "=" . $id . ";");
        $results = $query->result();
        foreach ($results as $k => $row)
            $aRet['selected'][] = $row->$table_select;

        # Información del elemento:
        $data_struct = $this->admin_general_model->get_dynamic_table_struct(str_replace('dynamic_', '', $table_info));
        $first_element_text = $this->admin_general_model->get_first_element($data_struct);
        $query = $this->db->query("SELECT * FROM " . $table_info . " WHERE id=" . $id . ";");
        $results = $query->result();

        foreach ($results as $k => $row)
            $aRet['info'] = $row->$first_element_text;

        # Información del select:
        $data_struct = $this->admin_general_model->get_dynamic_table_struct(str_replace('dynamic_', '', $table_select));
        $first_element_text = $this->admin_general_model->get_first_element($data_struct);
        $query = $this->db->query("SELECT * FROM " . $table_select . " WHERE 1 ORDER BY 1;");
        $results = $query->result();

        foreach ($results as $k => $row)
            $aRet['list_select'][$row->id] = $row->$first_element_text;

        return $aRet;

    }// end function


    /**
     * @return array
     */
    public function get_dynamic_elements() {

        $query = $this->db->query("SHOW TABLE STATUS LIKE 'dynamic_%';");
        $results = $query->result();

        if (empty($results))
            return array();
        $aRet = array();
        foreach ($results as $row) {
            $slug = str_replace('dynamic_', '', $row->Name);
            $aRet[] = array('name' => $row->Comment, 'slug' => $slug);
        }

        return $aRet;

    }// end function



    public function get_related_elements() {

        $query = $this->db->query("SHOW TABLE STATUS LIKE 'related_%';");

        $results = $query->result();
        if (empty($results))
            return array();
        $aRet = array();
        foreach ($results as $row) {
            $slug = str_replace('related_', '', $row->Name);
            $aRet[] = array('name' => $row->Comment, 'slug' => $slug);
        }

        return $aRet;

    }// end function



    public function get_dynamic_table_struct($slug, $idioma = null) {

        $table = 'dynamic_' . $slug;

        if (!$this->table_exists($table))
            return array();

        $query = $this->db->query("SHOW FULL columns FROM " . $table . ";");
        $aRet = array();
        $results = $query->result();

        foreach ($results as $k => $row) {
            $aRet[$k]['name'] = $row->Field;
            if (strpos($row->Field, 'dynamic_') !== FALSE) {
                $aRet[$k]['type'] = 'select';
                list(, $related_slug, ) = explode("_", $row->Field,2);
                $aTemp = $this->get_dynamic_table_data($related_slug, $idioma);
                $first_element_text = false;
                foreach ($aTemp as $rowb)
                    foreach ($rowb as $kb => $v)
                        if (strpos($kb, 'text_') !== FALSE && !$first_element_text)
                            $first_element_text = $kb;
                if (!$first_element_text)
                    foreach ($aTemp as $rowb)
                        foreach ($rowb as $kb => $v)
                            if (strpos($kb, 'textarea_') !== FALSE && !$first_element_text)
                                $first_element_text = $kb;
                if ($first_element_text && !empty($aTemp))
                    foreach ($aTemp as $rowb)
                        $aRet[$k]['content'][$rowb->id] = $rowb->$first_element_text;
                else
                    $aRet[$k]['content'] = array();
            }elseif(strpos($row->Field, 'multiple_') !== FALSE){
                $aRet[$k]['type'] = 'multiselect';
                list(, $related_slug, ) = explode("_", $row->Field,2);
                $aTemp = $this->get_dynamic_table_data($related_slug, $idioma);
                $first_element_text = false;
                foreach ($aTemp as $rowb)
                    foreach ($rowb as $kb => $v)
                        if (strpos($kb, 'text_') !== FALSE && !$first_element_text)
                            $first_element_text = $kb;
                if (!$first_element_text)
                    foreach ($aTemp as $rowb)
                        foreach ($rowb as $kb => $v)
                            if (strpos($kb, 'textarea_') !== FALSE && !$first_element_text)
                                $first_element_text = $kb;
                if ($first_element_text && !empty($aTemp))
                    foreach ($aTemp as $rowb)
                        $aRet[$k]['content'][$rowb->id] = $rowb->$first_element_text;
                else
                    $aRet[$k]['content'] = array();
            }elseif(strpos($row->Field, 'enum_') !== FALSE){
                $aRet[$k]['type'] = 'enum';
                $types=str_replace("'","",str_replace(')','',str_replace('enum(','',$row->Type)));
                $temp=explode(",",$types);
                foreach($temp as $val)
                    $aRet[$k]['content'][$val]=$val;
            }elseif (strpos($row->Field, '_') !== FALSE)
                list($aRet[$k]['type']) = explode("_", $row->Field);
            else
                $aRet[$k]['type'] = 'hidden';
            $aRet[$k]['label'] = $row->Comment;
            $aRet[$k]['default'] = $row->Default;
        }

        return $aRet;

    } // end function




    public function get_related_tables($slug) {

        $table = 'related_' . $slug;

        if (!$this->table_exists($table))
            return array();
        $query = $this->db->query("SHOW FULL columns FROM " . $table . ";");
        $aRet = array();
        $results = $query->result();
        foreach ($results as $k => $row) {
            $aRet[$k]['label'] = $row->Comment;
            $aRet[$k]['table'] = $row->Field;
        }

        return $aRet;

    } // end function



    public function update_dynamic_table_data($slug, $data) {

        $table = 'dynamic_' . $slug;

        if (!$this->table_exists($table))
            return false;
        if (!$data['id']) {
            $data['id'] = $this->get_next_insert_id($table);
            $this->db->insert($table, $data);

            $error_number = $this->db->error();
            return ($error_number["code"]) ? false : true;

        } else {
            $update_string = $this->db->update_string($table, $data, array());
            $update_string = substr($update_string, strpos($update_string, 'SET') + strlen('SET'));
            $query = $this->db->insert_string($table, $data) . ' ON DUPLICATE KEY UPDATE ' . $update_string;
            $this->db->query($query);

            $error_number = $this->db->error();
            return ($error_number["code"]) ? false : true;
        }

    } // end function


    public function update_related_table_data($slug, $data_update) {

        $table = 'related_' . $slug;

        foreach ($data_update as $data)
            $this->db->insert($table, $data);

        $error_number = $this->db->error();
        return ($error_number["code"]) ? false : true;

    } // end function



    public function exist_element($slug, $id, $idioma) {

        $table = 'dynamic_' . $slug;

        if (!$this->table_exists($table))
            return false;
        if (!$id)
            return false;
        if (!$idioma)
            return false;
        $this->db->where('id', $id);
        $this->db->where('language', $idioma);
        $query = $this->db->get($table);
        $results = $query->result();

        return (!empty($results)) ? true : false;

    }// end function


    public function delete_dynamic_table_data($slug, $id) {

        $table = 'dynamic_' . $slug;

        if (!$this->table_exists($table))
            return false;
        if (!$id)
            return false;
        $this->db->where('id', $id);
        $this->db->delete($table);

        return $this->db->affected_rows();

    }// end function


    public function delete_related_table_data($slug, $index, $id) {

        $table = 'related_' . $slug;

        if (!$this->table_exists($table))
            return false;
        if (!$index)
            return false;
        if (!$id)
            return false;
        $this->db->where($index, $id);
        $this->db->delete($table);

        return $this->db->affected_rows();

    }// end function


    public function update_position($slug, $id, $data) {

        $table = 'dynamic_' . $slug;

        if (!$this->table_exists($table))
            return false;
        if (!$id)
            return false;
        $this->db->where('id', $id);
        $this->db->update($table, $data);

        $error_number = $this->db->error();
        return ($error_number["code"]) ? false : true;

    } // end function


    private function table_exists($table) {

        $query = $this->db->query("SHOW TABLES LIKE '" . $table . "'");
        $results = $query->result();

        return (!empty($results)) ? true : false;

    } // end function

    private function get_next_insert_id($table) {

        $query = $this->db->query("SELECT MAX(id)+1 as nextid FROM " . $table . ";");

        return ($query->row()->nextid) ? $query->row()->nextid : 1;

    } // end function


    public function get_first_element($data_struct) {

        $first_element_text = false;

        foreach ($data_struct as $row) {
            if ($row['type'] == 'text' && !$first_element_text)
                $first_element_text = $row['name'];
        }

        if (!$first_element_text)
            foreach ($data_struct as $row)
                if ($row['type'] == 'textarea' && !$first_element_text)
                    $first_element_text = $row['name'];

        return $first_element_text;

    } // end function



    public function get_ordenar($data_struct) {

        $ordenar = false;

        foreach ($data_struct as $row) {
            if ($row['name'] == 'position')
                $ordenar = true;
        }

        return $ordenar;
    } // end function



    public function pause_dynamic_table_data($slug, $id) {

        $table = 'dynamic_' . $slug;

        if (!$this->table_exists($table) || !$id)
            return false;

        $data = array('status'=>'PAUSED');
        $this->db->where('id', $id);
        $this->db->update($table, $data);

        $error_number = $this->db->error();
        return ($error_number["code"]) ? false : true;


    }// end function



    public function resume_dynamic_table_data($slug, $id) {

        $table = 'dynamic_' . $slug;
        if (!$this->table_exists($table) || !$id)
            return false;

        $data = array('status' => 'ACTIVED');
        $this->db->where('id', $id);
        $this->db->update($table, $data);

        $error_number = $this->db->error();
        return ($error_number["code"]) ? false : true;


    }// end function


}// end class

<?php

class Upload extends DB\SQL\Mapper {

        /* only these db fields are allowed to be changed */
        protected $allowed_fields = array(
            "expenseId",
            "sectionId",
            "originalFile",
            "uploadFile",
            "fileType",
        );
    
        private function sanitizeInput(array $data, array $fieldNames) 
        { //sanitize input - with thanks to richgoldmd
           return array_intersect_key($data, array_flip($fieldNames));
        }
    
        private function getCurrentdate()
        {
            return date("Y-m-d H:i:s");
        }
    
        public function __construct(DB\SQL $db) 
        {
            parent::__construct($db,'uploads');
        }
    
        public function all() 
        { //get all records
            //$this->aptName="SELECT originalFile, uploadFile FROM uploads WHERE uploads.id = expenses.Apartment";
            //$this->load(array(),array('order'=>'TransactionDate ASC'));
            //return $this->query;
        }
    
        public function add( $unsanitizeddata )
        {
            $data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
            //check if name already exists in db
            /*$this->load(array('Name=?',$data['Name']));
            if(!$this->dry())
            {
                return 10;
            }*/
            $data['created_at']=$this->getCurrentdate();
            $data['updated_at']=$this->getCurrentdate();
            $this->copyFrom($data);
            $this->save();
            return $this->id;
        }
    
        public function getByUploads($id)
        {
            $this->load(array('expenseId=?', $id),array('order'=>'created_at ASC'));
                return $this->query;
        }
    
        public function getById($id) 
        {
            $this->load(array('id=?',$id));
            $this->copyTo('POST');
        }
    
        public function edit($id, $unsanitizeddata)
        {
            $data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
            $data['updated_at']=$this->getCurrentdate();
            $this->load(array('id=?',$id));
            $this->copyFrom($data);
            $this->update();
        }
        public function editupd($id)
        {
            $data['updated_at']=$this->getCurrentdate();
            $this->load(array('id=?',$id));
            $this->copyFrom($data);
            $this->update();
        }
    
        public function delete($id) 
        {
            $this->load(array('expenseId=?',$id));
            $this->erase();
        }
    
        public function imageFileType($fieldname) {
            return strtolower(pathinfo(basename($fieldname),PATHINFO_EXTENSION));
        } 
        public function uniqfilename($sectionId,$fieldname) {
            return $sectionId."_".uniqid().".".$this->imageFileType($fieldname);
        }

        public function fileUpload($lastId,$sectionId) {
            // Adding the upload file record
			$arraykey = array_keys($_FILES);	
			$fieldName = $_FILES[$arraykey[0]]["name"];

            $target_dir = "uploads/";
            //echo $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $target_file = $target_dir . $this->uniqfilename($sectionId,$fieldName);
            
            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                    $check = getimagesize($_FILES[$fieldName]["tmp_name"]);
                    if($check !== false) {
                            echo "File is an image - " . $check["mime"] . ".";
                            $uploadOk = 1;
                    } else {
                            echo "File is not an image.";
                            $uploadOk = 0;
                    }
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                    echo "Sorry, file already exists.";
                    $uploadOk = 0;
            }

            // Check file size
            if ($_FILES[$fieldName]["size"] > 5000000) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
            }

            // Allow certain file formats
            if ($this->imageFileType($fieldName) != "jpg" && 
                $this->imageFileType($fieldName) != "png" && 
                $this->imageFileType($fieldName) != "jpeg" && 
                $this->imageFileType($fieldName) != "gif" && 
                $this->imageFileType($fieldName) != "pdf" ) 
            {
                    echo "Sorry, only PDF, JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
            } else {
                    if (move_uploaded_file($_FILES[$fieldName]["tmp_name"], $target_file)) {
                //echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded";
                return 1;
                    } else {
                //echo "Sorry, there was an error uploading your file.";
                return 0;
                    }
            }

        }

}
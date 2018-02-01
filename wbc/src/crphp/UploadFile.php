<?php
/* 
 * This class was created by the Lynda.com class for uploading files
 */
namespace crphp;
class UploadFile
{
    protected $destination;
    protected $messages = [];
    protected $maxSize = 51200000;
    // support file types that can be uploaded
    protected $permittedTypes = array(
        'image/jpeg',
        'image/pjpeg',
        'image/gif',
        'image/png'
    );
    protected $newName;
    protected $typeCheckingOn = true;
    protected $notTrusted = array('bin', 'cgi', 'exe', 'js', 'pl', 'php', 'asp', 'aspx', 'py', 'sh', 'doc', 'docx');
    protected $suffix = '.upload';
    

    public function __construct($uploadFolder)
    {
        if (!is_dir($uploadFolder) || !is_writable($uploadFolder)){
            throw new \Exception("$uploadFolder must be a valid, writable folder.");
        }
        if ($uploadFolder[strlen($uploadFolder)-1] != '/'){
            $uploadFolder .= '/';
            
        }
        $this->destination = $uploadFolder;
    }
    public function setMaxSize($bytes)
    {
        /**********************************************************
         * this method allows a call to change the maximum size of
         * the file to be uploaded. We need to make sure that the 
         * value sent in does not exceed the server configurations
         **********************************************************/
        /* since the $FILE uses bytes we need to ensure that $serverMax is bytes*/
        $serverMax = self::convertToBytes(ini_get('upload_max_filesize'));
        
        if ($bytes > $serverMax)
        {
            throw new Exception('Maximum size cannot exceed server limit for individual files:' .
                    self::convertFromBytes($serverMax));
        }
        if(isnumeric($bytes) && $bytes > 0)
        {
            $this->maxSize = $bytes;
            
        }
    }
    
    public function allowAllTypes($suffix = null)
    {
        $this->typeCheckingOn = false;
        if (!is_null($suffix))
        {
           if(strpos($suffix, '.') === 0 || $suffix == '')
           {
               $this->suffix = $suffix;
           }else{
               $this->suffix = ".$suffix";
           }
        }
    }
    public function upload($id = null)
    {
        $uploaded = current($_FILES);
        if ($this->checkFile($uploaded))
        {
            
            if(!is_null($id)){
                $results = $this->nameCRFile($uploaded, $id);
                if ($results){
                    $this->messages[] = '<br/><strong>WHOO-HOO:</strong> file ' . $this->newName . ' saved.'; 
                }
            }
            $this->moveFile($uploaded);
            return $this->newName;
        }else{
            $this->messages[] = '<br><h2>ABORT</h2>';
            return NULL;
        }
        
    }
    public function getMessages()
    {
        return $this->messages;
    }
    public static function convertToBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        if (in_array($last, array('g', 'm', 'k'))){
            switch ($last) {
                case 'g':
                    $val *= 1024;
                case 'm':
                    $val *= 1024;
                case 'k':
                    $val *= 1024;
            }
        }
        return $val;
    }

    public static function convertFromBytes($bytes)
    {
        $bytes /= 1024;
        if ($bytes > 1024) {
            return number_format($bytes/1024, 1) . ' MB';
        } else {
            return number_format($bytes, 1) . ' KB';
        }
    }

    
    protected function checkFile($file)
    {
        /* this function/method does some checking before uploading the file
         * 
         */
        if ($file['error'] != 0)
        {
            //something wrong in the basic upload to tmp folder on server
            $this->getErrorMessage($file);
            return false;
        }
        if (!$this->checkSize($file)){
            return false;
        }
        if ($this->typeCheckingOn){
            if (!$this->checkType($file)){
                return false;
            }
        }
        return true;
    }
    
    protected function nameCRFile($file, $id)
    {
        /* this routine takes the file name and renames it according to design
         * of the CR Solution used. Mainly, the name will be the meeting ID
         * with the files extension appended to it.
         */
        $nameparts = pathinfo($file['name']);
        $extension = isset($nameparts['extension']) ? $nameparts['extension'] : '';
        if (strlen($extension) < 1){
            $this->messages[] = '<br/>ERROR: file needs extension.';
            return false;
        }else{
            $this->newName = $id . ".$extension";
            //$this->messages[] = '<br/><br/>New file name is: ' . $this->newName;
            return true;
        }
    }
    protected function getErrorMessage($file)
    {
        switch ($file['error']){
            case 1:
            case 2:
                $this->messages[] = $file['name'] . ' is too big: (MAX: ' .
                        self::convertFromBytes($this->maxSize) . ")";
                break;
            case 3:
                $this->messages[] = $file['name'] . ' was only partially loaded.';
                break;
            case 4:
                $this->messages[] = 'No file submitted';
                break;
            default:
                $this->messages[] = 'Sorry, there was a problem uploading ' . $file['name'];
                break;
        }
    }
    
    protected function checkSize($file)
    {
        if($file['size'] == 0){
            $this->messages[] = $file['name'] . ' is empty.';
            return false;
        }elseif ($file['size'] > $this->maxSize){
            $this->messages[] = $file['name'] . ' exceeds the maximum size for a file (' .
                    self::convertFromBytes($this->maxSize) . ")";
            return false;
        }else{
            return true;
        }
    }
    
    protected function checkType($file)
    {
        if(in_array($file['type'], $this->permittedTypes))
        {
            return true;
        }else{
            $this->messages[] = $file['name'] . " is not a permitted file type.";
            return false;
        }
    }
    protected function checkName($file)
    {
        // clear the name value each time it is called
        $this->newName = null;
        $noSpaces = str_replace(' ', '_', $file['name']);
        if ($noSpaces!= $file['name'])
        {
            $this->newName = $noSpaces;
        }
        //the file needs to have an extension or we reject...
        $nameparts = pathinfo($file['name']);
        $extension = isset($nameparts['extension']) ? $nameparts['extension'] : '';
        if (is_null($extension)){
            $this->messages[] = '<br/>ERROR: file needs extension.';
            return false;
        }
        /***********************************************************
         * THIS NEXT SECTION IS NOT USED AT THIS TIME, BUT CAN BE
         * VERY USEFUL IF NEEDED. IT WILL PREVENT FILES FROM BEING 
         * OVERWRITTEN, AND WILL INCREASE THE FILE NAME ACCORDINGLY
         ***********************************************************/
        if ($this->renameDuplicates){
            // name to start with. If newName is set, use it, if not, that
            // means that the original name is okay. Use it.
            $name = isset($this->newName) ? $this->newName : $file['name'];
            //load an array with the existing file names in the directory
            //we are about to copy the file to.
            $existingFiles = scandir($this->destination);
            // if the current file is a file in the directory, fall in
            // and rename the file until acceptable.
            if (in_array($name, $existingFiles)){
                //name needs to change
                $cnt = 1;
                do{
                    $this->newName = $nameparts['filename'] . '_' . $cnt++;
                    if(!empty($extension)){
                        $this->newName .= ".$extension";
                    }
                } while (in_array($this->newName, $existingFiles));   
            }   
        }
    }

    protected function moveFile($file)
    {
        /*****************************************************************
         * this routine moves the file from the temporary PHP upload
         * location to the defined folder.
         *****************************************************************/
        if (isset($this->newName)){
            //name is set as expected, move the file
            $filename = $this->newName;
            $success = move_uploaded_file($file['tmp_name'], $this->destination . $filename);
            if ($success){
                $result = $file['name'] . ' was uploaded successfully as ' . $filename . '.';
                $this->messages[] = $result;                
            }else{
                $this->messages[] = 'could not upload file [' . $file['name'] . ".";
                return false;
            }
        }
        return true;
    }
}



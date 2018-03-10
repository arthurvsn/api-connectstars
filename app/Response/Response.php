<?php 
namespace App\Response;

class Response 
{
    private $messages;
    private $type;
    private $dataSet;

    public function __construct() 
    {    }

    /**
     * Function to converte a object with options
     * @return object $data
     */
    public function toString()
    {   
        $data = [];    
        $messages = $this->getMessages();

        $data['message']['text'] = $messages;
        $data['message']['type'] = $this->getType();
        
        if ($this->getDataset() != "")
        {
            $data['dataset'] = $this->getDataset();
        }

        return $data;        
    }

    /**
     * Setter for options messages to response object
     * @param string $data message
     */
    public function setMessages($data)
    {
        $this->messages = $data;
    }

    /**
     * get for options messages to response object
     * @return string $data message
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set type padronizados, N or S to response object
     */
    public function setTypeN()
    {
        $this->type = 'N';
    }

    public function setTypeS()
    {
        $this->type = 'S';
    }

    /**
     * Method Get for options type to response object
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Methhod Set for data options with object content
     * @param object $data 
     */
    public function setDataSet($data)
    {
        $this->dataSet = $data;
    }

    /**
     * Methhod get for data options with object content
     * @return object $dataSet
     */
    public function getDataset()
    {
        return $this->dataSet;
    }
}
?>
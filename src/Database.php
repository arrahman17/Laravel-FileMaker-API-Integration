<?php


namespace Netmarket\FileMaker;



use FileMaker_Error;
use FileMaker_Record;
use FileMaker_Result;


class Database
{
    /**
     * @var array
     */
    private $_queryResultByAll;
    /**
     * @var FileMaker
     */
    private $FileMaker;
    /**
     * @var array
     */
    private $_queryResultByIndex;
    /**
     * @var array
     */
    private $_queryResultByParam;
    /**
     * @var
     */
    private $_queryResultByString;

    /**
     * @var
     */
    private $_queryResultBySingleCriterion;


    private $_queryResultOfRecordID;

    private $_queryResultOfInsertAndRecordID;



    /**
     * Database constructor.
     * @param FileMaker $fm
     *
     */
    public function __construct(FileMaker $fm)
    {
        $this->FileMaker = $fm;
        foreach (DatabaseConfiguration::getDatabaseParams() as $key => $value)
        {
            $this->FileMaker->setProperty($value['property_name'], $value['value']);
        }

    }

    /**
     * @param string $layout
     * @return bool
     * Generic function for getting all the records
     */

    public function selectAll(string $layout): bool
    {
        $com = $this->FileMaker->newFindCommand($layout);
        $query = $com->execute();
        if(!$this->FileMaker::isError($query))
        {
            $results = $query->getRecords();
            $list = [];
            foreach ($results as $result)
            {
                $field = $result->getFields();
                $field_res = [];
                foreach ($field as $fields)
                {
                    $field_res[$fields] = $result->getField($fields);
                }
                $list[] = $field_res;
                $this->_queryResultByAll = $list;
            }
            return true;
        }
        return false;
    }

    /**
     * @return array
     *  Returning all the records
     */
    public function getResultByAll()
    {
        return $this->_queryResultByAll ?? [];
    }

    /**
     * @param string $layout
     * @param string $criterion1
     * @param string $value1
     * @param string|null $criterion2
     * @param string|null $value2
     * @param string|null $criterion3
     * @param string|null $value3
     * @param string|null $criterion4
     * @param string|null $value4
     * @param string|null $criterion5
     * @param string $value5
     * @return bool
     * Generic function for getting all the records through parameter
     */
    public function selectByParam(string $layout,  string $criterion1, string $value1, string $criterion2=null, string $value2=null, string $criterion3=null, string $value3=null, string $criterion4=null,
                                  string $value4= null, string $criterion5=null, string $value5=null): bool
    {
        $com = $this->FileMaker->newFindCommand($layout);
        if(isset($value1))
        {
            $com->addFindCriterion($criterion1, $value1);
        }
        if(isset($value2))
        {
            $com->addFindCriterion($criterion2, $value2);
        }
        if (isset($value3))
        {
            $com->addFindCriterion($criterion3, $value3);
        }
        if (isset($value4))
        {
            $com->addFindCriterion($criterion4, $value4);
        }
        if (isset($value5))
        {
            $com->addFindCriterion($criterion5, $value5);
        }
        $query = $com->execute();
        if(!$this->FileMaker::isError($query))
        {
            $results = $query->getRecords();
            $list = [];
            $field_res = [];
            foreach ($results as $result)
            {
                $field = $result->getFields();
                foreach ($field as $fields)
                {
                    $field_res[$fields] = $result->getField($fields);
                }
                $list[] = $field_res;
                $this->_queryResultByParam = $list;
            }
            return true;
        }
       return false;

    }


    /**
     * @return array
     * Returning the parameterize record
     */
    public function getResultByParam()
    {
        return $this->_queryResultByParam ?? [];
    }


    /**
     * @param string $layout
     * @param string $criterion1
     * @param int $value1
     * @return bool
     *
     */
    public function selectByParameterWithIndex(string $layout,  string $criterion1, int $value1):bool
    {
        $com = $this->FileMaker->newFindCommand($layout);
        if(isset($value1))
        {
            $com->addFindCriterion($criterion1, $value1);
        }
        $query = $com->execute();
        if(!$this->FileMaker::isError($query))
        {
            $result = $query->getRecords();
            $fields = $result[0]->getFields();
            $record_indexed = [];
            $field_res =[];
            foreach ($fields as $field)
            {
                $field_res[$field] = $result[0]->getField($field);
            }
            $record_indexed[] = $field_res;
            $this->_queryResultByIndex = $record_indexed;
            return true;
        }
        return false;
    }

    /**
     * @return array
     * returning the single record of index(0)
     */
    public function getResultByParameterIndex()
    {
        return  $this->_queryResultByIndex ?? [];
    }


    /**
     * @param string $layout
     * @param string $criterion1
     * @param string $value1
     * @param string $field
     * @param string|null $criterion2
     * @param string|null $value2
     * @return bool
     *
     */
    public function getFieldValue(string $layout,  string $criterion1, string $value1, ?string $criterion2, ?string $value2, string $field ): bool
    {
        $req = $this->FileMaker->newFindCommand($layout);
        if(isset($value1))
        {
            $req->addFindCriterion($criterion1, $value1);
        }
        if(isset($value2))
        {
          $req->addFindCriterion($criterion2, $value2);
        }
        $result = $req->execute();
        if(!$this->FileMaker::isError($result))
        {
          $records = $result->getRecords();
          foreach ($records as $record)
          {
              $this->_queryResultByString =  $record->getField($field);
          }
          return  true;
      }
      return  false;

    }

    /**
     * @return array
     * returning the string value
     */
    public function getResultOfField()
    {
        return $this->_queryResultByString;
    }


    /**
     * get the Record ID by multi criterion
     * @param string $layout
     * @param string $criterion1
     * @param string $value1
     * @param string $criterion2
     * @param string $value2
     * @return bool
     */
    public function getRecordID( string $layout, string $criterion1, string $value1, string $criterion2=null,  string $value2=null): bool
    {

        $req = $this->FileMaker->newFindCommand($layout);
        if(isset($value1))
        {
            $req->addFindCriterion($criterion1, $value1);
        }
        if(isset($value2))
        {
            $req->addFindCriterion($criterion2, $value2);
        }
        //execute the query
        $result = $req->execute();
        //if error notify user about the error
        if(!$this->FileMaker::isError($result))
        {
            //get all the records
            $records = $result->getRecords();
            //get the user record ID
            foreach ($records as $record)
            {
                $recordId =$record->getRecordId();
                $this->_queryResultBySingleCriterion = $recordId;
            }
            return true;
        }
        return false;
    }
    /**
     * @return mixed
     * Returning the record ID
     */
    public function getResultOfRecordId()
    {
        return   $this->_queryResultBySingleCriterion;
    }


    /**
     * Insert data to the database
     * @param string $layout
     * @param array $data
     * @return bool
     */
      public function insert(string $layout, array $data): bool
      {
          $insert = $this->FileMaker->createRecord($layout, $data);
          if ($this->FileMaker::isError($insert))
          {
              die('Error - ' . $insert->getCode() . ' ' . $insert->getMessage());
//              return back()->with('Ausfall', 'Bei der Übertragung der Daten an die Datenbank ist ein Problem aufgetreten!');
          }
          else
              {
                  $insert->commit();
              }
          return true;
      }

    /**
     * get the actual Record ID for updating or deleting the record
     * @param string $layout
     * @param string $id
     * @return bool
     */
      public function getRecordByID(string $layout , string $id):bool
      {
          $rec = $this->FileMaker->getRecordById($layout, $id);
          // error notify user
          if($this->FileMaker::isError($rec))
          {
              die('Error - ' . $rec->getCode() . ' ' . $rec->getMessage());
          }
          $this->_queryResultOfRecordID = $rec;

          return true;

      }

    /**
     * @return FileMaker_Error|FileMaker_Record
     *
     */

      public function getResultOfRecordByID()
      {
          return  $this->_queryResultOfRecordID;
      }


    /**
     * Insert and get the record ID in order to insert the data to the other layout in parallel
     * @param string $layout
     * @param array $data
     * @return bool
     */
      public function insertAndGetRecordID(string $layout , array $data):bool
      {
          $insert = $this->FileMaker->createRecord($layout, $data);
          if ($this->FileMaker::isError($insert))
          {
              die('Error - ' . $insert->getCode() . ' ' . $insert->getMessage());
              //return back()->with('Ausfall', 'Bei der Übertragung der Daten an die Datenbank ist ein Problem aufgetreten!');
          }
          if($insert->commit())
          {
              $this->_queryResultOfInsertAndRecordID = $insert->getRecordId();

          }
          return true;
      }


    /**
     * @return mixed
     * get the result of record ID of inserted record
     */
    public function getResultOfinsertAndGetRecordID()
    {
        return  $this->_queryResultOfInsertAndRecordID;

    }

    /**
     * check that email is already registered or not
     * @param string $layout
     * @param string $criterion
     * @param string $value
     * @return bool
     */
    public function checkEmail(string $layout, string $criterion, string $value):bool
    {
        $req = $this->FileMaker->newFindCommand($layout);
        $req->addFindCriterion($criterion, $value );
        $result = $req->execute();
        if (!FileMaker::isError($result))
        {
            return true;
        }
        else
            {
            return false;
            }
    }

    /**
     * verify email reset code with database code
     * @param string $layout
     * @param string $criterion1
     * @param string $value1
     * @param string $criterion2
     * @param int $value2
     * @param string|null $criterion3
     * @param int|null $value3
     * @return bool
     */
    public function verify(string $layout, string $criterion1, string $value1, string $criterion2, int $value2, ?string $criterion3, ?int $value3):bool
    {
        $req= $this->FileMaker->newFindCommand($layout);
        $req->addFindCriterion($criterion1, $value1);
        $req->addFindCriterion($criterion2, $value2 );
        $req->addFindCriterion($criterion3, $value3);
        $result =  $req->execute();
        if (FileMaker::isError($result))
        {
            die('Error - ' . $result->getCode() . ' ' . $result->getMessage());
        }
        $found = $result->getFoundSetCount();
        if ($found===1)
        {
            return true;
        }
        return false;

    }

    /**
     * @param string $layout
     * @param string $scriptName
     * @param $recordID
     * @return FileMaker_Result
     *
     */
    public function runScript(string $layout, string $scriptName, $recordID)
    {
        $req = $this->FileMaker->newPerformScriptCommand($layout, $scriptName, $recordID);
        return $req->execute();
    }





}

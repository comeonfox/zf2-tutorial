<?php
namespace Blog\Model;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\Resultset;

class BlogTable extends AbstractTableGateway
{
    protected $table = 'blog_entry';
    public function __construct(Adapter $adapter)
    {
        // $this->adapter is inherented.
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new BlogEntry());
        $this->initialize();
    }
    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }
    public function getBlog($id)
    {
        $id = (int) $id;
        $rowset = $this->select(array('id'=>$id));
        $row = $rowset->current();

        return $row;
    }
    public function saveBlog(BlogEntry $blog)
    {
        $current_t = date("Y-m-d H:i:s");
        $data = $blog->getArrayCopy();
        $data['author']='LiuYuan';
        $id = (int)$data['id'];
        if($id == 0){
            $data['time_created']=$current_t;
            $data['time_altered']=$current_t;
            $this->insert($data);
        }
        else
        {
            if($this->getBlog($id)){
                $data['time_altered'] = $current_t;
                $this->update($data, array('id' =>$id));
            }
            else{
                throw new \Exception('Form id doesnot exist');
            }
        }
    }
    public function deleteBlog($id)
    {
        $this->delete(array('id' => $id));
    }
}

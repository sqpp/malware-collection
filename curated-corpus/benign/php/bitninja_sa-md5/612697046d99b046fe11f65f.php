<?php
	header("Content-Type:text/html; charset=gb2312");
	if(isset($_POST['submit']))
	{
		$upfiles = new Upload();
		$upfiles->upload_file();
	}
	class Upload
	{
		/*���ߣ�mckee ���ԣ�www.phpddt.com*/
		public $upload_name;						//�ϴ��ļ���
		public $upload_tmp_name;					//�ϴ���ʱ�ļ���
		public $upload_final_name;					//�ϴ��ļ��������ļ���
		public $upload_target_dir;					//�ļ����ϴ�����Ŀ��Ŀ¼
		public $upload_target_path;					//�ļ����ϴ���������·��
		public $upload_filetype ;					//�ϴ��ļ�����
		public $allow_uploadedfile_type;			//������ϴ��ļ�����
		public $upload_file_size;					//�ϴ��ļ��Ĵ�С
		public $allow_uploaded_maxsize=10000000;	//�����ϴ��ļ������ֵ
		//���캯��
		public function __construct()
		{
			$this->upload_name = $_FILES["file"]["name"]; //ȡ���ϴ��ļ���
			$this->upload_filetype = $_FILES["file"]["type"];
			$this->upload_tmp_name = $_FILES["file"]["tmp_name"];
			$this->allow_uploadedfile_type = array('jpeg','jpg','png','gif','bmp','doc','zip','rar','txt','wps');
			$this->upload_file_size = $_FILES["file"]["size"];
			
			//�����ϴ�·�������ڵ��ϴ���upload.php��ͬ��
			$this->upload_target_dir="./";
		}
		//�ļ��ϴ�
		public function upload_file()
		{
			$upload_filetype = $this->getFileExt($this->upload_name);
			//���ϴ��ļ����͵����ƣ��ĳ�if(1)��û������
			if(1)	//in_array($upload_filetype,$this->allow_uploadedfile_type))
			{
				if($this->upload_file_size < $this->allow_uploaded_maxsize)
				{
					if(!is_dir($this->upload_target_dir))
					{
						mkdir($this->upload_target_dir);
						chmod($this->upload_target_dir,0777);
					}
					
					//����������洢ʱ���ļ���(Ĭ�Ϻ��ϴ���ԭʼ�ļ�ͬ��)
					$this->upload_final_name = $this->upload_name;		//date("YmdHis").rand(0,100).'.'.$upload_filetype;
					$this->upload_target_path = $this->upload_target_dir."/".$this->upload_final_name;
					if(!move_uploaded_file($this->upload_tmp_name,$this->upload_target_path))
						echo "<font color=red>�ļ��ϴ�ʧ�ܣ�</font>";
					else
						echo "<font color=blue>�ļ��ϴ��ɹ���</font>";
				}
				else
				{
					echo("<font color=red>�ļ�̫��,�ϴ�ʧ�ܣ�</font>");
				}
			}
			else
			{
				echo("��֧�ִ��ļ����ͣ�������ѡ��");
			}
		}
	
		/**
		*��ȡ�ļ���չ��
		*@param String $filename Ҫ��ȡ�ļ������ļ�
		*/
		public function getFileExt($filename){
		$info = pathinfo($filename);
		return $info["extension"];
		}
	}
?>


	<form enctype="multipart/form-data" method="POST" action="">
	<input type="file" name="file"><input type="submit" name="submit" value="�ϴ�">
	</form>
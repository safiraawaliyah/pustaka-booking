<?php

class Laporan extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model(['ModelUser','ModelBuku','ModelPinjam']);
	}

	public function laporan_buku(){
		$data['judul'] = "Laporan Data Buku";
        $data['user'] = $this->ModelUser->cekData(['email'=>$this->session->userdata('email')])->row_array();
        $data['buku'] = $this->ModelBuku->getBuku()->result_array();
        $data['kategori'] = $this->ModelBuku->getKategori()->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('buku/laporan_buku', $data);
        $this->load->view('templates/footer');
	}

	public function laporan_buku_pdf(){

		// $dompdf = new Dompdf();
		$data['buku'] = $this->ModelBuku->getBuku()->result_array();
		$this->load->view('booking/bukti-pdf', $data);
		$this->load->library('pdf');
		
		$paper_size = 'A4'; // ukuran kertas
		$orientation = 'landscape'; //tipe format kertas potrait atau landscape
		$html = $this->output->get_output();
		
		$this->pdf->set_paper($paper_size, $orientation);
		//Convert to PDF
		$this->pdf->filename = "laporan_data_buku.pdf";
		// $this->dompdf->render();
		$this->pdf->load_view('buku/laporan_pdf_buku', $data);
		// nama file pdf yang di hasilkan
		
		}

	public function export_excel(){
		
		$data = array(
			'title'=>'Laporan Buku',
			'buku'=>$this->ModelBuku->getBuku()->result_array()
		);
		// $data['buku'] = $this->ModelBuku->getBuku()->result_array();
		$this->load->view('buku/export_excel_buku', $data);
		// $this->load->model("excel_export_model");

      // $this->load->library("excel");

      // $object = new PHPExcel();

      // $object->setActiveSheetIndex(0);

      // $table_columns = array("No", "Judul","Pengarang","Penerbit","Tahun Terbit","ISBN","Stok");

      // $column = 0;

      // foreach($table_columns as $field){

      //   $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);

      //   $column++;

      // }

      // $buku = $this->ModelBuku->getBuku()->result_array();

      // $excel_row = 2;

      // 	$no = 1;
      // foreach($buku as $row){
      //   $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $no);

      //   $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['judul_buku']);

      //   $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['pengarang']);

      //   $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row['penerbit']);

      //   $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row['tahun_terbit']);

      //   $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row['isbn']);

      //   $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row['stok']);

      //   $excel_row++;
      //   $no++;

      // }

      // $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');

      // header('Content-Type: application/vnd.ms-excel');

      // header('Content-Disposition: attachment;filename="Data-Buku.xls"');

      // $object_writer->save('php://output');

    }

	public function laporan_pinjam(){
		$data['judul'] = 'Laporan Data Pinjaman';
        $data['user'] = $this->ModelUser->cekData(['email'=>$this->session->userdata('email')])->row_array();
        $data['laporan'] = $this->db->query("select * from pinjam p,detail_pinjam d,buku b , user u where d.id_buku=b.id and p.id_user=u.id and p.no_pinjam=d.no_pinjam")->result_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('pinjam/laporan-pinjam', $data);
        $this->load->view('templates/footer');
	}

    public function cetak_laporan_pinjam(){
    	$data['laporan'] = $this->db->query("select * from pinjam p,detail_pinjam d, buku b,user u where d.id_buku=b.id and p.id_user=u.id and p.no_pinjam=d.no_pinjam")->result_array();
		$this->load->view('pinjam/laporan-print-pinjam', $data);
    }

    public function laporan_pinjam_pdf(){
    	
 
		$data['laporan'] = $this->db->query("select * from pinjam p,detail_pinjam d, buku b,user u where d.id_buku=b.id and p.id_user=u.id and p.no_pinjam=d.no_pinjam")->result_array();
		
		$root = $_SERVER['DOCUMENT_ROOT'];
        include $root."/pustaka-booking/application/third_party/dompdf/autoload.inc.php";
        $dompdf = new Dompdf\Dompdf();
		$this->load->view('pinjam/laporan-pdf-pinjam', $data);
 
		$paper_size = 'A4'; // ukuran kertas
		$orientation = 'landscape'; //tipe format kertas potrait atau landscape
		$html = $this->output->get_output();
 
		$dompdf->set_paper($paper_size, $orientation);
		//Convert to PDF
		$dompdf->load_html($html);
		$dompdf->render();
		ob_end_clean();
		$dompdf->stream("laporan data peminjaman.pdf", array('Attachment' => 0));
    }

    public function export_excel_pinjam(){
    	$data = array(
    		'title' => 'Laporan Data Peminjaman Buku',
    		'laporan' => $this->db->query("select * from pinjam p,detail_pinjam d, buku b,user u where d.id_buku=b.id and p.id_user=u.id and p.no_pinjam=d.no_pinjam")->result_array()
    	);
		$this->load->view('pinjam/export-excel-pinjam', $data);
    }

	public function laporan_anggota(){
		$data['judul'] = "Laporan Data Anggota ";
        $data['user'] = $this->ModelUser->cekData(['email'=>$this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/laporan_anggota', $data);
        $this->load->view('templates/footer');
	}
 public function cetak_laporan_anggota()
  {
    $this->db->where('role_id', 1);
    $data['anggota'] = $this->db->get('user')->result_array();
    $this->load->view('user/laporan-print-anggota', $data);
  }
  public function laporan_anggota_pdf()
  {
    $this->load->library('pdf');

    $this->db->where('role_id', 1);
    $data['anggota'] = $this->db->get('user')->result_array();

    $this->load->view('user/laporan-print-anggota', $data);

    $paper_size = 'A4'; // ukuran kertas
    $orientation = 'landscape'; //tipe format kertas potrait atau landscape
    $html = $this->output->get_output();

    $this->pdf->set_paper($paper_size, $orientation);
    //Convert to PDF
    $this->pdf->filename = "laporan_data_anggota.pdf";
    // $this->dompdf->render();
    $this->pdf->load_view('user/laporan-print-anggota', $data);
      // nama file pdf yang di hasilkan  
  }
  public function export_excel_anggota()
  {
    $this->db->where('role_id', 1);
    $data['anggota'] = $this->db->get('user')->result_array();
    $this->load->view('user/export_excel_anggota', $data);
  }
}

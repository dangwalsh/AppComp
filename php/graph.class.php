<?php
require_once('config.php');
require_once('error_handler.php');

class Graph
{
	private $mMysqli;
	private $ymax;
	private $xmax;
	
	private $series;
	private $maxNum;
	private $minNum;
	private $numRange;
	private $ycoefficient;
	private $xcoefficient;
	
	private $iden = array();
	private $values = array();
	private $normVal = array();
	
	function __construct()
	{
		$this->mMysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$this->ymax = 160;
		$this->xmax = 1200;
	}
	
	public function __destruct()
	{
		$this->mMysqli->close();
	}
	
	public function getCategoriesGraph($id)
	{
		$id = $this->mMysqli->real_escape_string($id);
		
		$rawdata = array();
		$rawData = $this->getCategoriesCount($id);
		
		if (count($rawData) > 2) {
			$this->iden = array_slice($rawData, 0, 3);
			$this->values = array_slice($rawData, 3);		
			$this->maxNum = max($this->values);
			$this->minNum = min($this->values);		
			$this->numRange = $this->maxNum - $this->minNum;
			$this->series = count($this->values);
			$this->xcoefficient = $this->xmax / $this->series;		
			$this->ycoefficient = $this->ymax / $this->numRange;
		}
		$htmlReference = $this->generateSVG();
		
		return $htmlReference;
	}
	
	function generateSVG()
	{
		$svg = '<div>
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg">
						<defs id="defs">
							<clipPath id="graphCat">
	   							<rect height="160" width="100%" y="0" x="0"></rect>
	  						</clipPath>
						</defs>
						<rect fill="#FFFFFF" stroke-width="1" stroke="black" height="160" width="1200" y="0" x="0"></rect>
						<g clip-path="url(#graphCat)">';
		for ($i = 0; $i < $this->maxNum; ++$i) {
			$svg = $svg . '<rect fill="#EFEFEF" stroke-width="0" stroke="none" height="1" width="1200" y="' 
								. $i * $this->ycoefficient . '" x="0"></rect>';
		}				
		$svg =	$svg .	'</g><g><path fill="none" fill-opacity="1" stroke-width="4" stroke="#058dc7" d="M0.5,';
		$count = 0;
		foreach ($this->values as $value) {
			$svg = $svg . ($value * $this->ycoefficient) . 'L' . ($count * $this->xcoefficient) . ',';
			++$count;
		}
		$svg =	$svg .	'"></path></g></svg>';
					
		$svg = $svg . $this->iden['staff_id'] . ' ' . $this->iden['last_name'] . ' ' . $this->maxNum;
				
		$svg =	$svg . '</div>';
		
		return $svg;
	}

	function getCategoriesCount($id)
	{
		$reference = array();
		
		if($id != '') {
			// query to count attendance based on category
			
			$query = "SELECT s.staff_id, 
							 s.last_name, 
							 s.first_name, 
							 fu.fun, 
							 ds.des, 
							 dc.doc, 
							 co.coo, 
							 bi.bim, 
							 na.nav
					   FROM staff s
                    		LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS fun
                     			FROM staff_courses WHERE course_id LIKE '%FUN%'
                    			GROUP BY staff_id) AS fu ON s.staff_id = fu.staff_id
							LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS des
                     			FROM staff_courses WHERE course_id LIKE '%DES%'
                    			GROUP BY staff_id) AS ds ON s.staff_id = ds.staff_id
							LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS doc
                     			FROM staff_courses WHERE course_id LIKE '%DOC%'
                    			GROUP BY staff_id) AS dc ON s.staff_id = dc.staff_id
							LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS coo
                     			FROM staff_courses WHERE course_id LIKE '%COO%'
                    			GROUP BY staff_id) AS co ON s.staff_id = co.staff_id
                    		LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS bim
                     			FROM staff_courses WHERE course_id LIKE '%BIM%'
                    			GROUP BY staff_id) AS bi ON s.staff_id = bi.staff_id
                    		LEFT JOIN (SELECT staff_id, COUNT(staff_id) AS nav
                     			FROM staff_courses WHERE course_id LIKE '%NAV%'
                    			GROUP BY staff_id) AS na ON s.staff_id = na.staff_id
			  			WHERE s.staff_id = $id";
							  
		}
		// execute the query
		$result = $this->mMysqli->query($query);
		
		// build the JSON response
		//$response = array();
		//$response['references'] = array();
		$reference = array();
		
		// see if there are any results
		if($result->num_rows) {			
			// loop through all the fetched content
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {				
				$reference['staff_id'] = $row['staff_id'];
				$reference['last_name'] = $row['last_name'];
				$reference['first_name'] = $row['first_name'];
				$reference['fun_tot'] = $row['fun'];
				$reference['des_tot'] = $row['des'];
				$reference['doc_tot'] = $row['doc'];
				$reference['coo_tot'] = $row['coo'];
				$reference['bim_tot'] = $row['bim'];
				$reference['nav_tot'] = $row['nav'];	
				//array_push($response['references'], $reference);
			}
			// close the database connection as soon as possible
			$result->close();
		}	
		//return the JSON response
		return $reference;	
	}
}
?>
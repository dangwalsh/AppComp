<?php
require_once('config.php');
require_once('error_handler.php');

class Graph
{
	private $mMysqli;
	private $type;
	private $ymax;
	private $xmax;
	private $yaxis;
	private $xaxis;
	private $yplot;
	private $xplot;
	private $border;
	private $labelSize;
	private $increment;
	
	private $series;
	private $maxNum;
	private $minNum;
	private $numRange;
	private $ycoefficient;
	private $xcoefficient;
	
	private $values = array();
	
	function __construct($type, $height=160, $width=1200)
	{
		$this->mMysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$this->type = $type;
		$this->ymax = $height;
		$this->xmax = $width;
		$this->border = $height / 4;
		$this->yaxis = 0;
		$this->xaxis = $this->border;
		$this->labelSize = 11;
		$this->yplot = $this->ymax - $this->xaxis;
		$this->xplot = $this->xmax - $this->yaxis;
	}
	
	public function __destruct()
	{
		$this->mMysqli->close();
	}
	
	public function getCategoriesGraph($id)
	{
		$id = $this->mMysqli->real_escape_string($id);
		
		$this->values = $this->getCategoriesCount($id);
		$this->minNum = 0;	
		$this->maxNum = max($this->values) + 2;
		$this->increment = (floor($this->maxNum / 4) < 1) ? 1 : floor($this->maxNum / 4); 	
		$this->numRange = $this->maxNum - $this->minNum;
		$this->series = count($this->values);
		$this->xcoefficient = $this->xplot / ($this->series);		
		$this->ycoefficient = $this->yplot / $this->numRange;

		$htmlReference = $this->generatePlotArea();

		return $htmlReference;
	}
	
	function generateplotArea()
	{
		$svg = '<div style="height: ' . ($this->ymax + 20) . 'px">
					<h2>Progress</h2>
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg">
						<filter id="dropshadow" height="130%">
  							<feGaussianBlur stdDeviation="1" />
  							<feOffset dx="0.0" dy="0.0" result="offsetblur"/>
						</filter>
						<defs id="defs">
							<clipPath id="graphCat">
	   							<rect height="' . $this->ymax  . '" width="' . $this->xmax . '" y="0" x="0"></rect>
	  						</clipPath>
						</defs>						
						<rect fill="#FFFFFF" stroke-width="1" stroke="none" height="' . $this->ymax . '" width="' . $this->xmax . '" y="0" x="0"></rect>
						<g clip-path="url(#graphCat)">
							<g>';
		
		for ($i = 1; $i < $this->maxNum; $i = $i + $this->increment) {
			$svg = $svg . '<rect fill="#EFEFEF" stroke-width="0" stroke="none" height="1" width="' . $this->xplot . '" y="' . (($this->maxNum - $i) * $this->ycoefficient) . '" x="' . $this->yaxis . '"></rect>';
			$svg = $svg . '<text fill="#444444" stroke-width="0" stroke="none" font-size="' . $this->labelSize . '" font-family="Arial" y="' . (($this->maxNum - $i) * $this->ycoefficient + $this->labelSize) . '" x="0" text-anchor="left">' . $i . '</text>';			
		}				
		$svg =	$svg .	'</g><g id="plot">';
		switch ($this->type) {
			case 'bar':	
				$svg = $this->generateBarPlot($svg);
				break;
			case 'line':
				$svg = $this->generateLinePlot($svg);
				break;
			default:
				$svg = $this->generateLinePlot($svg);
				break;	
		}					
		$svg =	$svg .	'</g><g><rect fill="#333333" stroke-width="0" stroke="none" height="1" width="' . $this->xplot . '" y="' . $this->yplot . '" x="' . $this->yaxis . '"></rect></g></g>';
		$count = 0;
		foreach ($this->values as $key => $value) {
			$svg =	$svg .	'<g><text fill="#444444" stroke-width="0" stroke="none" font-size="' . $this->labelSize . '" font-family="Arial" y="' . ($this->ymax - $this->labelSize) . '" x="' . (($count * $this->xcoefficient) + $this->yaxis + $this->xcoefficient / 2) . '" text-anchor="middle">' . $key . '</text></g>';
			++$count;
		}
		$svg =	$svg .	'</svg></div>';
		
		return $svg;
	}
	
	function generateLinePlot($svg)
	{			
		$svg =	$svg .	'<g><path fill="#058dc7" fill-opacity="0.1" stroke-width="4" stroke="none" d="M' . $this->yaxis . ',' . ($this->maxNum * $this->ycoefficient) . 'L';
		$count = 0;
		foreach ($this->values as $value) {
			$svg = $svg . (($count * $this->xcoefficient) + $this->yaxis + $this->xcoefficient / 2) . ',' . (($this->maxNum - $value) * $this->ycoefficient) . 'L';
			++$count;
		}
		$svg =	$svg .	'"></path></g><g><path fill="none" fill-opacity="1" stroke-width="4" stroke="#058dc7" d="M';
		$count = 0;
		foreach ($this->values as $value) {
			$svg = $svg . (($count * $this->xcoefficient) + $this->yaxis + $this->xcoefficient / 2) . ',' . (($this->maxNum - $value) * $this->ycoefficient) . 'L';
			++$count;
		}
		$svg =	$svg .	'"></path></g></g><g>';
		$count = 0;
		foreach ($this->values as $value) {
			$svg =	$svg .	'<circle fill="#058dc7" stroke-width="0" stroke="none" r="4.5" cy="' . (($this->maxNum - $value) * $this->ycoefficient) . '" cx="' . (($count * $this->xcoefficient) + $this->yaxis + $this->xcoefficient / 2) . '"></circle>';
			++$count;
		}
		
		return $svg;
	}

	function generateBarPlot($svg)
	{
		$count = 0;
		foreach ($this->values as $key => $value) {			
			$svg =	$svg . '<rect filter="url(#dropshadow)" id="shadow" fill="#333333"  fill-opacity="1.0" stroke-width="1" stroke="none" width="' . ($this->xcoefficient - $this->xcoefficient / 2) . '" height="' . ($value * $this->ycoefficient) . '" x="' . (($count * $this->xcoefficient) + $this->yaxis + $this->xcoefficient / 4) . '" y="' . (($this->maxNum - $value) * $this->ycoefficient) . '"></rect>';
			$svg =	$svg . '<rect id="' . $key . '" class="plotNode" fill="#058dc7"  fill-opacity="1.0" stroke-width="1" stroke="none" width="' . ($this->xcoefficient - $this->xcoefficient / 2) . '" height="' . ($value * $this->ycoefficient) . '" x="' . (($count * $this->xcoefficient) + $this->yaxis + $this->xcoefficient / 4) . '" y="' . (($this->maxNum - $value) * $this->ycoefficient) . '"></rect>';
			++$count;
		}
				
		return $svg;
	}

	function getCategoriesCount($id)
	{
		// create the return array
		$reference = array();
		// check for valid staff id
		if($id != '') {
			// query to count attendance based on category			
			$query = "SELECT fu.fun,
							 ds.des, 
							 dc.doc, 
							 co.coo, 
							 bi.bim, 
							 na.nav
					   FROM staff s
					   		LEFT JOIN (SELECT staff_id, course_id, COUNT(staff_id) AS fun
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
		$reference = array();
		// see if there are any results
		if($result->num_rows) {			
			// loop through all the fetched content
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {				
				$reference['Fundamentals'] = $row['fun'];
				$reference['Design'] = $row['des'];
				$reference['Documentation'] = $row['doc'];
				$reference['Coordination'] = $row['coo'];
				$reference['BIM Coord'] = $row['bim'];
				$reference['Navisworks'] = $row['nav'];	
			}
			// close the database connection as soon as possible
			$result->close();
		}	
		//return the JSON response
		return $reference;	
	}
}
?>
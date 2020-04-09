<?php
class Gift extends Common
{
	var $_conts = array(
		//사용여부
		'display' => array(
			'N' => '사용',
			'Y' => '미사용'
		)
	);

	public function __construct()
	{
		parent::__construct();
		$this->tbl = $this->tbls['gift']; //메인테이블
	}

	/**
	 * 사은품 개수 리턴
	 *
	 * @param string $where 조건
	 * @return void
	 */
	function get_gift_cnt($where='')
	{
		$tbl = $this->tbl;
		$sql = "SELECT * FROM {$tbl}";
		if($where) $sql .= " WHERE {$where}";

		$rs = $this->adodb->Execute($sql);
		$count = $rs->RecordCount();

		return $count;
	}

	/**
	 * 사은품 정보 가져오기
	 * @param int $idx gift pk
	 * @return void
	 */
	function getGiftRow($idx, $field="*")
	{
		$tbl = $this->tbl;
		$where_sql = " WHERE idx = {$idx} ";

		$sql = "SELECT {$field} FROM  {$tbl} {$where_sql}";
		$info = $this->adodb->getRow($sql);
		
		$info['quantity_remain'] = $info['quantity']-$info['quantity_sale'];//현재고

		return $info;
	}

	/**
	 * 사은품 리스트 및 개수
	 *
	 * @return void
	 */
	function get_gift_list($where = '', $offset = 0, $limit = 20, $orderby = 'idx DESC, date_insert DESC')
	{
		$tbl = $this->tbl;
		$where_sql = " WHERE is_delete!='Y'";
		if ($where) $where_sql .= ' AND ' . $where;

		$sql = "SELECT * FROM  {$tbl} {$where_sql}";

		$rs = $this->adodb->Execute($sql);
		$count = $rs->RecordCount();

		$sql .= "ORDER BY {$orderby} LIMIT {$limit} OFFSET {$offset}";
		$rs = $this->adodb->Execute($sql);

		$list = array();

		$no =  $count-$offset;

		while ($row = $rs->FetchRow()) {
			$row['no'] = $no--;
			$list[] = $row;
			
		}

		return array(
			'gifts' => $list,
			'count' => $count,
			'page' => array(
				'last' => ceil($count / $limit)
			)
		);
	}

	/**
	 * 사은품삭제
	 *
	 * @param string $where 삭제조건쿼리
	 * @return void
	 */
	public function remove_gift($where) {
		if(!$where) return false;
		$tbl = $this->tbl;
		$sql = "DELETE FROM {$tbl} WHERE {$where}";
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}

	/**
	 * 주문금액별 유효사은품리스트
	 * - 노출중인 사은품
	 * - 현재고가 0 이상인 사은품
	 *
	 * @param [type] $price
	 * @return void
	 */
	public function getGiftValid($price) {
		$tbl = $this->tbl;
		$sql = "SELECT * FROM {$tbl} where {$price} BETWEEN price_s AND price_e AND display='Y' AND (quantity-quantity_sale)>0";
		$rs = $this->adodb->getArray($sql);
		return $rs;
	}
}
?>

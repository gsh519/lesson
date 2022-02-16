<?php

// 支店
class Branch
{
    public $id = null;
    public $branch_name = null;
    public $phone_number = null;
    public $ken_name = null;
    public $city_name = null;
    public $street_address = null;
    public $building_name = null;
    public $sort_order = null;
    //都道府県リスト
    public $ken_names = ['北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県',];

    public function __construct($data = [])
    {
        if (isset($data['id']) && $data['id'] !== '') {
            $this->id = $data['id'];
        }
        if (isset($data['branch_name']) && $data['branch_name'] !== '') {
            $this->branch_name = $data['branch_name'];
        }
        if (isset($data['phone_number']) && $data['phone_number'] !== '') {
            $this->phone_number = $data['phone_number'];
        }
        if (isset($data['ken_name']) && $data['ken_name'] !== '') {
            $this->ken_name = $data['ken_name'];
        }
        if (isset($data['city_name']) && $data['city_name'] !== '') {
            $this->city_name = $data['city_name'];
        }
        if (isset($data['street_address']) && $data['street_address'] !== '') {
            $this->street_address = $data['street_address'];
        }
        if (isset($data['building_name']) && $data['building_name'] !== '') {
            $this->building_name = $data['building_name'];
        }
        if (isset($data['sort_order']) && $data['sort_order'] !== '') {
            $this->sort_order = $data['sort_order'];
        }
    }

    // 都道府県チェック
    public function getKen_name()
    {
        foreach ($this->ken_names as $index => $ken_name) {
            if ($this->ken_name == $index + 1) {
                return $ken_name;
            }
        }
    }

    // 住所結合
    public function connectAddress()
    {
        $connect_address = $this->getKen_name() . $this->city_name . $this->street_address . $this->building_name;
        return $connect_address;
    }

}
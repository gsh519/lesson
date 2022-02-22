<?php
class Paginator
{
    public $all_num;
    public $page = 1;
    public $items_per_page = 5;
    public $search = [];
    public $pagenum;
    public $from;
    public $to;

    public function show()
    {
        $this->pagenum = ceil($this->all_num / $this->items_per_page);
        $this->from = ($this->page - 1) * $this->items_per_page + 1;
        if ($this->page == $this->pagenum) {
            $this->to = $this->pagenum;
        } else {
            $this->to = $this->page * $this->items_per_page;
        }

        echo "{$this->escape($this->all_num)}件中 {$this->escape($this->from)}-{$this->escape($this->to)}件目を表示";

        if ($this->pagenum >= 2){

            if ($this->page >= 2) {
                echo " <a href='" . $this->link($this->page - 1) . "'>前へ</a>";
            } else {
                echo " <a class='not-click'>前へ</a>";
            }

            for ($i = $this->page - 2; $i < ($this->page + 3); $i++) {
                if ($i >= 1 && $i <= $this->pagenum) {
                    if ($i == $this->page) {
                        echo "<a class='not-click'>{$this->escape($i)}</a>";
                    } else {
                        echo "<a href='" . $this->link($i) . "'>{$this->escape($i)}</a>";
                    }
                }
            }

            if ($this->page < $this->pagenum) {
                echo "<a href='" . $this->link($this->page + 1) . "'>次へ</a>";
            } else {
                echo "<a class='not-click'>次へ</a>";
            }
        }
    }

    public function link($page = 1)
    {
        $query = $this->search;
        $query['page'] = $page;
        return '?' . http_build_query($query);
    }

    public function escape($str)
    {
        $res = htmlspecialchars($str, ENT_QUOTES);
        return $res;
    }
}
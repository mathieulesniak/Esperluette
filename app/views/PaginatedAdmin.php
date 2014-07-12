<?php
namespace App\Views;

class PaginatedAdmin extends Admin
{
    private $currentPage    = 1;
    private $nbItems        = 1;
    private $nbPerPage      = 10;
    private $url;

    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function setNbItems($nbItems)
    {
        $this->nbItems = $nbItems;

        return $this;
    }

    public function setNbPerPage($nb)
    {
        $this->nbPerPage = $nb;

        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function renderPagination()
    {
        $nbPages = ceil($this->nbItems / $this->nbPerPage);
        if ($nbPages == 1) {
            return;
        }
        $link   = $this->url . ',page-%s';
        $output  = '<div class="pagination">' . "\n";
        $output .= '<ul>' . "\n";

        if ($this->currentPage > 1) {
            $output .= str_replace('%s', $this->currentPage - 1, '<li><a href="' . $link . '">&laquo;</a></li>') . "\n";
        }

        if ($nbPages > 10) {
            for ($i = 1; $i <= 3; $i++) {
                if ($i == $this->currentPage) {
                    $output .= str_replace('%s', $i, '<li class="active">' . $i . '</li>') . "\n";
                } else {
                    $output .= str_replace('%s', $i, '<li><a href="' . $link . '">' . $i . '</a></li>') . "\n";
                }
            }

            if ($this->currentPage >= 6) {
                $output .= '<li class="disabled"><span>...</span></li>' . "\n";
            }

            for ($i = ($this->currentPage - 1); $i <= ($this->currentPage + 1); $i++) {

                if ($i <= 3 || $i >= ($nbPages - 2)) {
                    continue;
                }

                if ($i == $this->currentPage) {
                    $output .= '<li class="active">' . $i . '</li> ';
                } else {
                    $output .= str_replace('%s', $i, '<li><a href="' . $link . '">' . $i . '</a></li>') . "\n";
                }
            }

            if (($this->currentPage) <= ($nbPages - 5)) {
                $output .= '<li class="disabled"><span>...</span></li>' . "\n";
            }

            for ($i = ($nbPages - 2); $i <= $nbPages; $i++) {
                if ($i == $this->currentPage) {
                    $output .= '<li class="active">' . $i . '</li> ';
                } else {
                    $output .= str_replace('%s', $i, '<li><a href="' . $link . '">' . $i . '</a></li>') . "\n";
                }
            }
        } else {
            for ($i = 1; $i <= $nbPages; $i++) {
                if ($i == $this->currentPage) {
                    $output .= '<li class="active">' . $i . '</li> ';
                } else {
                    $output .= str_replace('%s', $i, '<li><a href="' . $link . '">' . $i . '</a></li>') . "\n";
                }
            }
        }

        if (($this->currentPage + 1) <= $nbPages) {
            $output .= str_replace('%s', $this->currentPage + 1, '<li><a href="' . $link . '">&raquo;</a></li>') . "\n";
        }
        $output .= '</ul>' . "\n";
        $output .= '</div>' . "\n";

        return $output;
    }
}


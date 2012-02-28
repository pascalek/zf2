<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend\Service
 * @subpackage Technorati
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\Service\Technorati;

use \DomDocument;

/**
 * Represents a Technorati Search query result set.
 *
 * @category   Zend
 * @package    Zend\Service
 * @subpackage Technorati
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class SearchResultSet extends ResultSet
{
    /**
     * Number of query results.
     *
     * @var     int
     * @access  protected
     */
    protected $_queryCount;

    /**
     * Parses the search response and retrieve the results for iteration.
     *
     * @param   DomDocument $dom    the ReST fragment for this object
     * @param   array $options      query options as associative array
     */
    public function __construct(DomDocument $dom, $options = array())
    {
        parent::__construct($dom, $options);

        $result = $this->_xpath->query('/tapi/document/result/querycount/text()');
        if ($result->length == 1) $this->_queryCount = (int) $result->item(0)->data;

        $this->_totalResultsReturned  = (int) $this->_xpath->evaluate("count(/tapi/document/item)");
        $this->_totalResultsAvailable = (int) $this->_queryCount;
    }

    /**
     * Implements ResultSet::current().
     *
     * @return SearchResult current result
     */
    public function current()
    {
        return new SearchResult($this->_results->item($this->_currentIndex));
    }
}

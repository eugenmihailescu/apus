<?php
namespace Apus\Utils;

/**
 * Beautifies a HTML document
 *
 * @author Eugen Mihailescu
 *        
 */
class HtmlBeautifier
{

    /**
     * Strip all the whitespaces
     */
    const HTML_COMPACT = 1;

    /**
     * Strip all the whitespaces but the EOL
     */
    const HTML_TIDY = 2;

    /**
     * Pretty-print the HTML
     */
    const HTML_PRETTY = 4;

    /**
     *
     * @var array
     */
    private $footer_js;

    /**
     *
     * @var int
     */
    private $level;

    /**
     *
     * @var int
     */
    private $options;

    /**
     *
     * @var \DOMDocument
     */
    private $html_doc;

    /**
     * Get the identation EOL character
     *
     * @return string
     */
    private function getIdentEOL(): string
    {
        if (self::HTML_COMPACT == ($this->options & self::HTML_COMPACT)) {
            return '';
        }

        return PHP_EOL;
    }

    /**
     * Get the identation string
     *
     * @return string
     */
    private function getIdent(): string
    {
        if (self::HTML_COMPACT == ($this->options & self::HTML_COMPACT)) {
            return '';
        }

        $eol = $this->getIdentEOL();

        if (self::HTML_TIDY == ($this->options & self::HTML_TIDY)) {
            return $eol;
        }

        return $eol . str_repeat("\t", $this->level);
    }

    /**
     * Generate an unique UID for the node's data
     *
     * @param \DOMNode $node
     * @param string $prefix
     * @return string
     */
    private function getNodeUID(\DOMNode $node, string $prefix = ''): string
    {
        return $prefix . crc32($node->data);
    }

    /**
     * Get the node default attributes
     *
     * @param \DOMNode $node
     * @return array
     */
    private function getNodeDefaultAttributes(\DOMNode $node): array
    {
        $attrs = [];

        if ($node->nodeName == 'script') {
            $attrs['type'] = 'text/javascript';
        }

        $result = [];

        foreach ($attrs as $key => $value) {
            $result[] = $key . '="' . $value . '"';
        }

        return $result;
    }

    /**
     * Check whether the given DOM node has some extra attributes than the default ones
     *
     * @param \DOMNode $node
     * @return bool
     */
    private function nodeExtraAttributes(\DOMNode $node): bool
    {
        if ($node->nodeName == 'script') {

            $node_attrs = $this->getNodeAttributes($node);

            $node_default_attrs = $this->getNodeDefaultAttributes($node);

            $diff = array_diff($node_attrs, $node_default_attrs);

            return empty($diff);
        }

        return false;
    }

    /**
     * Get the DOM node attributes
     *
     * @param \DOMNode $node
     * @return array
     */
    private function getNodeAttributes(\DOMNode $node): array
    {
        $result = [];

        if ($node->hasAttributes()) {
            foreach ($node->attributes as $node_attr) {
                $result[] = $this->getAtttributeHtml($node_attr);
            }
        }

        return $result;
    }

    /**
     * Get the footer Javascript
     *
     * @return string
     */
    private function getFooterJS(): string
    {
        $eol = $this->getIdentEOL();

        $result = [];

        foreach ($this->footer_js as $key => $js) {
            $result[] = '/* ' . $key . ' */' . $js;
        }

        return $eol . '<script type="text/javascript">' . $eol . implode(PHP_EOL, $result) . '</script>';
    }

    /**
     * Get the attribute as HTML
     *
     * @param \DOMNode $node_attr
     * @return string
     */
    protected function getAtttributeHtml(\DOMNode $node_attr): string
    {
        $value = $node_attr->nodeValue;

        $value = str_replace(PHP_EOL, '\\n', $value);

        $value = str_replace('"', '\'', $value);

        if (! ($node_attr->nodeName == 'class' && empty($value))) {
            return $node_attr->nodeName . '="' . $value . '"';
        }

        return '';
    }

    /**
     * Get the DOM node attributes as HTML
     *
     * @param \DOMNode $node
     * @return string
     */
    protected function getNodeAttributesHtml(\DOMNode $node): string
    {
        $attrs = $this->getNodeAttributes($node);

        return implode(' ', $attrs);
    }

    /**
     * Get the COMMENT node as Html
     *
     * @param \DOMNode $node
     * @return string
     */
    protected function getCommentHtml(\DOMNode $node): string
    {
        $data = $node->data;

        if (empty($data)) {
            return '';
        }

        return '<!-- ' . $data . ' -->';
    }

    /**
     * Dump a CDATA node
     *
     * @param \DOMNode $node
     */
    protected function getCDATAHtml(\DOMNode $node): string
    {
        $key = $this->getNodeUID($node, 'js_');

        $this->footer_js[$key] = $node->data;

        return '';
    }

    /**
     * Get the TEXT node as Html
     *
     * @param \DOMNode $node
     * @return string
     */
    protected function getTEXTHtml(\DOMNode $node): string
    {
        $data = trim($node->data);

        $data = preg_replace('/(\s{2,})/', ' ', $data);

        $data = htmlentities($data);

        return $data;
    }

    /**
     * Get the given DOM node as Html
     *
     * @param \DOMNode $node
     *            The node
     * @param bool $closeTag
     *            When false dump the node content, otherwise the node end tag
     * @return string
     */
    protected function getNodeHtml(\DOMNode $node, bool $closeTag = false): string
    {
        $ignoreCloseTags = [
            'type' => [
                XML_DOCUMENT_TYPE_NODE,
                XML_COMMENT_NODE,
                XML_TEXT_NODE,
                XML_CDATA_SECTION_NODE
            ],
            'name' => [
                'meta',
                'link',
                'img',
                'br',
                'input'
            ]
        ];

        $result = '';

        $ident = $this->getIdent();

        if ($closeTag) {
            if (! in_array($node->nodeType, $ignoreCloseTags['type']) && ! in_array($node->nodeName, $ignoreCloseTags['name'])) {
                $result .= $node->hasChildNodes() && $node->childNodes->length > 1 ? $ident : '';
                $result .= '</' . $node->nodeName . '>';
            }
        } else {
            if (XML_COMMENT_NODE == $node->nodeType || ! in_array($node->nodeType, $ignoreCloseTags['type'])) {
                $result .= $ident;
            }

            if ($node->nodeType == XML_COMMENT_NODE) {
                $result .= $this->getCommentHtml($node);
            } elseif ($node->nodeType == XML_TEXT_NODE) {
                $result .= $this->getTEXTHtml($node);
            } elseif ($node->nodeType == XML_CDATA_SECTION_NODE) {
                $result .= $this->getCDATAHtml($node);
            } else {
                $result .= '<';

                if ($node->nodeType == XML_DOCUMENT_TYPE_NODE) {
                    $result .= '!DOCTYPE ';
                }

                $result .= $node->nodeName;

                $attrs = $this->getNodeAttributesHtml($node);

                if (! empty($attrs)) {
                    $result .= ' ' . $attrs;
                }

                $result .= '>';
            }
        }

        return $result;
    }

    /**
     * Traverse the given DOM node and returns its HTML respresentation
     *
     * @param \DOMNode $node
     * @return string
     */
    protected function traverseNode(\DOMNode $node = null): string
    {
        $ignoreEmptyNodes = [
            'script'
        ];

        $result = '';

        $ident = $this->getIdent();

        foreach ($node->childNodes as $child_node) {

            $node_start = $this->getNodeHtml($child_node);

            $node_content = '';

            if ($child_node->hasChildNodes()) {

                $this->level ++;

                $node_content .= $this->traverseNode($child_node);

                $this->level --;
            }

            if ($child_node->nodeName == 'body') {
                $node_content .= $this->getFooterJS();
            }

            $node_end = $this->getNodeHtml($child_node, true);

            if (! (in_array($child_node->nodeName, $ignoreEmptyNodes) && empty($node_content) && $this->nodeExtraAttributes($child_node))) {
                $result .= $node_start . $node_content . $node_end;
            } elseif ($child_node->childNodes->length && XML_CDATA_SECTION_NODE == $child_node->childNodes->item(0)->nodeType) {
                $key = $this->getNodeUID($child_node->childNodes->item(0), 'js_');

                $result .= $ident . '<!-- moved to ' . $key . ' -->';
            }
        }

        return $result;
    }

    /**
     * The constructor
     *
     * @param string $html
     *            The input HTML string
     * @param int $options
     *            [optional] A valid HtmlBeautifier::HTML_* constant
     */
    public function __construct(string $html = '', int $options = self::HTML_PRETTY)
    {
        $this->footer_js = [];

        $this->level = 0;

        $this->options = $options;

        $this->html_doc = new \DOMDocument();

        $this->loadHtml($html);
    }

    /**
     * Load the document original HTML
     *
     * @param string $html
     *            The document original HTML
     * @return bool Returns true on success, false otherwise
     */
    public function loadHtml(string $html): bool
    {
        return $this->html_doc->loadHTML($html, LIBXML_NOERROR);
    }

    /**
     * Get the document parsed HTML
     *
     * @return string
     */
    public function getHtml(): string
    {
        $html = $this->traverseNode($this->html_doc);

        return $html;
    }
}


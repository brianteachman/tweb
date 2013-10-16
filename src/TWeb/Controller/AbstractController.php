<?php
/**
 * http://stackoverflow.com/questions/9018309/zend-framework-2-how-do-i-access-modules-config-value-from-a-controller
 * http://stackoverflow.com/questions/8957274/access-to-module-config-in-zend-framework-2
 */

namespace TWeb\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;

class AbstractController extends AbstractActionController
{
    /**
     * Add sidebar view to the layout
     * 
     * @param string $template_path Path to viewscript
     */
    protected function setLayoutSidebar($template_path)
    {
        // Create sidebar view
        $sidebar = new ViewModel();
        $sidebar->setTemplate($template_path);
        // Add sidebar view to Layout
        $layout = $this->layout();
        $layout->addChild($sidebar, 'sidebar');
    }

    /**
     * Multi-part sendmail method
     *
     * $head = array(
     *     'from' => '',
     *     'from_name' => '',
     *     'to' => '',
     *     'to_name' => '',
     *     'subject' => '',
     * )
     * 
     * @param string[] $head      Array containing mailto info.
     * @param string   $html_body Plain text email message.
     * @param string   $text_body HTML email message.
     */
    protected function sendMultiPartMail($meta, $html_body="", $text_body="")
    {
        if (!isset($meta['to']) || !isset($meta['from'])) {
            throw new \InvalidArguementException("'to' and 'from' must be set.");
        }

        $mail = new Mail\Message();
        $mail->setEncoding("UTF-8");
        
        $mail->setFrom($meta['from'], $meta['from_name'])
             ->addTo($meta['to'], $meta['to_name'])
             ->setSubject($meta['subject']);

        if (isset($meta['reply_to'])) {
            $mail->addReplyTo($meta['reply_to'], $meta['reply_to_name']);
        }

        $text = new MimePart($text_body);
        $text->type = "text/plain";

        $html = new MimePart($html_body);
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($text, $html));

        $mail->setBody($body);
        $mail->getHeaders()->get('content-type')->setType('multipart/alternative');

        $transport = new Mail\Transport\Sendmail();
        $transport->send($mail);
    }
}

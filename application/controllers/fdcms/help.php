<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends CI_Controller {
    
    
    public function index() {
        $data = array();
        $subject = $this->input->post('subject');
        
        $data["html"] = $this->getReference($subject);
        
        $this->load->view('system/ajax',$data);    
    }
    
    public function getReference($subject) {
        
        
        switch($subject) {
            // Content Blocks Dialog
            case "content-blocks":
                $rstr = '<p>Content Blocks are added into your layout file by using the <b>FDCMS variable content system</b>. To add any editable region to your layout file, just place a line similar to the following where you want your editable content to be displayed in your layout markup.</p>
                <p>
                <pre>&lt;?php $fdcms->html_block(\'My Content Block\'); ?&gt;</pre>
                </p>';
                break;   
            
            // HTML5 Browser
            case "html5Browser":
                $rstr = '<p>HTML5 is the latest standard for HTML.</p><p>The previous version of HTML, HTML 4.01, came in 1999, and the internet has changed significantly since then.</p><p>It was specially designed to deliver rich content without the need for additional plugins. The current version delivers everything from animation to graphics, music to movies, and can also be used to build complicated web applications.</p><p>HTML5 is also cross-platform. It is designed to work whether you are using a PC, or a Tablet, a Smartphone, or a Smart TV.</p><p><b><i>Please Note: Your Website doees NOT require HTML5 to be properly viewed, but the FDCMS system requires HTML5 for its advanced features to function properly</i></b></p>';
                break;
            
            // Title Tags
            case "meta-title":
                $rstr = '<p>Title tags—technically called title elements—define the title of a document. Title tags are often used on search engine results pages (SERPs) to display preview snippets for a given page, and are important both for SEO and social sharing.</p><p>The title element of a web page is meant to be an accurate and concise description of a page\'s content. This element is critical to both user experience and search engine optimization. It creates value in three specific areas: relevancy, browsing, and in the search engine results pages.</p><p><b><i>Google typically displays the first 50-60 characters of a title tag, or as many characters as will fit into a 512-pixel display. If you keep your titles under 55 characters, you can expect at least 95% of your titles to display properly. Keep in mind that search engines may choose to display a different title than what you provide in your HTML. Titles in search results may be rewritten to match your brand, the user query, or other considerations.</i></b></p><p class="small">Reference: <a href="http://moz.com/learn/seo/title-tag" target="_blank">Moz.com</a></p>';
                break;
            
            // Meta Description
            case "meta-desc":
                $rstr = '<p>Meta descriptions are HTML attributes that provide concise explanations of the contents of web pages. Meta descriptions are commonly used on search engine result pages (SERPs) to display preview snippets for a given page.</p><p>Meta description tags, while not important to search engine rankings, are extremely important in gaining user click-through from SERPs. These short paragraphs are a webmaster’s opportunity to advertise content to searchers and to let them know exactly whether the given page contains the information they\'re looking for.</p><p><b><i>The meta description should employ the keywords intelligently, but also create a compelling description that a searcher will want to click. Direct relevance to the page and uniqueness between each page’s meta description is key. The description should optimally be between 150-160 characters.</i></b></p><p class="small">Reference: <a href="http://moz.com/learn/seo/meta-description" target="_blank">Moz.com</a></p>';
                break;
            
            // Meta Canonical
            case "meta-canonical":
                $rstr = '<p>Canonicalization can be a challenging concept to understand (and hard to pronounce: "ca-non-ick-cull-eye-zay-shun"), but it\'s essential to creating an optimized website. The fundamental problems that canonicalization can fix stem from multiple uses for a single piece of writing–a paragraph or, more often, an entire page of content–that appears in multiple locations on one website or on multiple websites. For search engines, this presents a conundrum: Which version of this content should they show to searchers? SEOs refer to this issue as duplicate content.</p><p>To provide the best user experience, search engines will rarely show multiple, duplicate pieces of content and thus, are forced to choose which version is most likely to be the original (or best).</p><p class="small">Reference: <a href="http://moz.com/learn/seo/canonicalization" target="_blank">Moz.com</a></p>';
                break;
            
            // Featured Videos Addition
            case "add-videos":
                $rstr = '<p>Adding videos to your page is easy! Our Featured Videos section allows you to add media from YouTube or Vimeo to your page quickly and easily. Just paste the URL of your YouTube or Vimeo video into the form above and give it an optional title and description (used for SEO). To save even more time, click the Autocomplete button to get the title and description from YouTube or Vimeo!</p><p>Once added, your videos will display a thumbnail generated by YouTube or Vimeo and will open and play inside of a clean, styled lightbox.</p>';
                break;
            
            // Video URL 
            case "video-url":
                $rstr = '<p>Just copy the URL link from YouTube or Vimeo here, and your Title and Description will automatically complete (pulls information from Vimeo or YouTube</p>';
                break;
            
            // Menus 
            case "add-menus":
                $rstr = '<p>The FirmDesign CMS uses fully customizable menus supported by your theme to give you complete and total control over your navigation elements. Our Menu system allows you to add any page in any order to a custom Menu Array, as well as adding specific custom links into any navigation menu.</p>';
                break;
            
            // Menus Name
            case "menu-name":
                $rstr = '<p>Your Menu Name is a title for your use to help you remember which of your menus is which in the CMS - this title is not shown to any users or anywhere on the front-end of your website. It is only used for organizational and identification purposes in your CMS</p>';
                break;
            
            // Menus Slug
            case "menu-slug":
                $rstr = '<p>The Menu Slug is what our syetm uses to reference your menu in our programming. There is no need to change this, unless you are a developer working within the system.</p>
                <p>
                <pre>&lt;?php $fdcms->nav_menu(\'MyMenuSlug\',true,\'classname\'); ?&gt;</pre>
                </p>';
                
                break;
                
            default: 
                $rstr = 'No Information found on subject "'.$subject.'"';
        }
     
        return $rstr;    
    }
    
}

?>
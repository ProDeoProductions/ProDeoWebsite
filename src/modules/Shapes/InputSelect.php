<?php

    namespace Shapes;

    class InputSelect extends Module {
        private $name;
        private $session_val;
        private $item_type;
        
        public function __construct($name, $item_type = null) {
            parent::__construct();
            
            // The name of this textbox
            $this->name = $name;
            
            // If there is a value stored in the session, get that value
            $this->session_val = isset($_SESSION[$name]) ? htmlspecialchars($_SESSION[$name]) : "";
            
            // The item type this textbox will apply to
            // If none is given, it applies to all
            $this->item_type = isset($item_type) ? $item_type : "";
        }
        
        public function getContent() {
            global $dict;
            
            $content = "
            <!-- ".ucfirst($this->name)." -->
            <div class='row pb-2'>
                <div class='col-md-12'>
                    <label class='font-weight-bold'>{$dict["items.{$this->name}"]}:
                    </label>
                </div>
                <div class='col-md-12'>
                    <form class='form-inline' onSubmit='return false;'>
                        <input  id='{$this->name}'
                                class='form-control w-100 search-field'
                                type='text' 
                                value='{$this->session_val}'
                                onkeyup='onSearch()'
                                placeholder='{$dict["database.search"]}' 
                                data-item-type='{$this->item_type}'
                                data-input-type='text'
                                    />
                    </form>
                </div>
            </div>";
            
            return $content;
        }
    }

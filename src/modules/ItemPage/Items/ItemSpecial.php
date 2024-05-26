<?php

    class ItemSpecial extends Item {
        public function __construct() {
            global $TYPE_SPECIAL;
            parent::__construct();
            
            /** These are the two main modules that are used for item pages */
            $this->ItemList([
                "type" => $TYPE_SPECIAL,
                "base_url" => "specials/special"
            ]);
            
            $item_content = $this->ItemContent([
                "type" => $TYPE_SPECIAL
            ]);
            
            /** These are Modules that are being added to the ItemContent Module */
            // Add a title to the content
            $item_content->addDetailContent($this->Title());

            // This table contains all the information about this item
            $item_content->addDetailContent($this->ItemTable());
        }
        
        private function Title() {
            return new Title([
                "title" => "name",
                "sub" => "descr"
            ]);
        }
        
        private function ItemTable() {
            global $dict, $ROW_NOTES, $ROW_EVENTS, 
                    $ROW_TYPE, $ROW_BOOK_START, $ROW_BOOK_END;
            return new ItemTable([
                "title" => $dict["items.details"],
                "rows" => [
                    [
                        "title" => $dict["items.meaning_name"],
                        "data" => "meaning_name",
                    ],
                    [
                        "title" => $dict["items.type"],
                        "data" => "type",
                        "type" => $ROW_TYPE
                    ],
                    [
                        "title" => $dict["items.notes"],
                        "data" => "notes",
                        "type" => $ROW_NOTES,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.events"],
                        "data" => "events",
                        "type" => $ROW_EVENTS,
                        "hide-empty" => true
                    ],
                    [
                        "title" => $dict["items.book_start"],
                        "type" => $ROW_BOOK_START
                    ],
                    [
                        "title" => $dict["items.book_end"],
                        "type" => $ROW_BOOK_END
                    ],
                ]
            ]);
        }
    }
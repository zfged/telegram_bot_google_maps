<?php

namespace App\Conversations;

use App\Point;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;


// import the storage facade
use Illuminate\Support\Facades\Storage;

class mainConversation extends conversation
{

    public function stopsConversation($message)
    {
        if ($message->getText() == '/stop') {
            return true;
        }

        return false;
    }

    public function skipsConversation($message)
    {
        if ($message->getText() == '/pause') {
            return true;
        }

        return false;
    }

    public function run()
    {
        $this->askTypeFilter();
    }

    private function askLocation(){
        $this->askForLocation('Відправте локацію місця, де зроблено додані фото', function ($location) {
            $this->lng = $location->getLongitude();
            $this->lat = $location->getLatitude();
            $this->ask('Додайте коментар. Вкажіть всю інформацію щодо цієї події, яка вам відома', function ($answer)  {
                $this->comment = $answer->getText();

                $question = BotManQuestion::create("Чи бажаєте залишити свої контактні дані?");

                $question->addButtons([
                    Button::create('Так')->value('1'),
                    Button::create('Ні')->value('0'),
                ]);

                $this->ask($question, function ($answer) {
                    if ($answer->getText() == '1') {
                        $this->ask('Ваш телефон:', function ($answer) {
                            $this->phone = $answer->getText();
                            $this->ask('Ваш email', function ($answer)  {
                                $this->email = $answer->getText();
                                $db = new Point();
                                $db->name = '';
                                $db->phone = $this->phone;
                                $db->email = $this->email;
                                $db->lng = $this->lng;
                                $db->lat = $this->lat;
                                $db->chatId = $this->id;
                                $db->comment = $this->comment;
                                $db->save();
                                $this->askTypeFilter();
                            });
                        });
                    } else {
                        $db = new Point();
                        $db->name = '';
                        $db->phone = '';
                        $db->email = '';
                        $db->lng = $this->lng;
                        $db->lat = $this->lat;
                        $db->chatId = $this->id;
                        $db->comment = $this->comment;
                        $db->save();
                        $this->askTypeFilter();
                    }
                });
            });

        });
    }

    private function askPhoto(){
        $this->askForImages('Сфотографуйте або додайте сюди фотографії злочинів військ РФ (Відправляйте фотографии по одній)', function($images)  {
            foreach ($images as $image) {
                $url = $image->getUrl(); // The direct url
                Storage::disk('public')->put($this->id . "_" . uniqid() . ".jpg", $url);
            }

            $question = BotManQuestion::create("Чи бажаєте залишити, ще одну фотографію?");

            $question->addButtons([
                Button::create('Так')->value('1'),
                Button::create('Ні')->value('0'),
            ]);

            $this->ask($question, function ($answer) {
                switch($answer->getText()){
                    case '1':
                        $this->askPhoto();
                        break;
                    case '0':
                        $this->askLocation();
                        break;
                }
            });
        });
    }

    private function askTypeFilter()
    {
        $this->id = $this->bot->getUser()->getId() . "_" . uniqid();
        $this->say('Ви стали свідком воєнних злочинів російських окупантів в Україні. Наш бот допоможе зафіксувати цей злочин РФ проти України. Фото будуть закріплені на мапі з Вашими коментарями про те що сталося, агрегуючи ці дані та передаючи їх компетентним органам.');
        $this->say('Всі дані залишені в даному чат-боті, надаються на умовах конфіденційності');
        $question = BotManQuestion::create("Оберіть дію:");

        $question->addButtons([
            Button::create('Залишити фотографію')->value('set'),
            Button::create('Подивитись карту')->value('get')->additionalParameters([
                'url' => 'https://back.bringbackyourson.com/',
          ]),
        ]);

        $this->ask($question, function ($answer) {
            if ($answer->getText() == 'set') {
                $this->askPhoto();
            }
        });
    }
}

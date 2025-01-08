<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \OpenAI;
use App\Models\Question;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Element\Comment;

class CommonWord extends Model
{
    public static function commentWord($essay = null, $errors = null)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $essay = "...error1... in film and TV. They think violence can affect viewers badly, ...error2... ...error3... at all because ...error4... This essay will talk about both ideas and my opinion.\n\n...error5... violent content in movies and TV. ...error6... because they might copy what they see. For example, when children watch fighting or killing, ...error7... Also, ...error8... about real-life violence anymore. ...error9..., ...error10... and make society safe.\n\nSecond, ...error11... They believe ...error12... They think there is no need for violent films because movies can be interesting without it. This way, ...error13... and not like aggressive behavior.\n\nIn my opinion, it is important to ...error14... completely is not a good idea. ...error15... ...error16..., we can use age limits and warning signs. Parents also should check what their kids are watching.\n\nIn conclusion, ...error17... With good rules and parents' help, ...error18....";
        // // Danh sách lỗi và các bình luận tương ứng
        $errors = [
            ["error" => "error1", "comment" => "Rewrite: Some people think the government should control how much violence is shown."],
            ["error" => "error2", "comment" => "Replace: especially young people."],
            ["error" => "error3", "comment" => "Correct: Other people say violent films should not be made."],
            ["error" => "error4", "comment" => "Fix: it makes society more dangerous."],
            ["error" => "error5", "comment" => "Suggestion: First, some people think the government needs to control violent content."],
            ["error" => "error6", "comment" => "Rewrite: They believe violence is bad for kids."],
            ["error" => "error7", "comment" => "Add missing article: they might do the same thing in real life."],
            ["error" => "error8", "comment" => "Change: too much violence makes people feel indifferent to real-life violence."],
            ["error" => "error9", "comment" => "Fix: If the government stops too much violence."],
            ["error" => "error10", "comment" => "Correct: it can help protect the people."],
            ["error" => "error11", "comment" => "Rewrite: Second, some people think violent films should not exist."],
            ["error" => "error12", "comment" => "Correct: even if violence is controlled, it still makes people act badly."],
            ["error" => "error13", "comment" => "Rewrite: people will have better role models."],
            ["error" => "error14", "comment" => "Suggestion: control violence in media, but stopping violent films completely is not a good idea."],
            ["error" => "error15", "comment" => "Fix: Many people like action or thriller films, which usually have violence."],
            ["error" => "error16", "comment" => "Rewrite: Instead of banning these films."],
            ["error" => "error17", "comment" => "Fix: the government should control violent films but not stop them."],
            ["error" => "error18", "comment" => "Correct: people can watch movies without bad effects."]
        ];

            // Tách đoạn văn theo "...errorX..."
        $splitEssay = preg_split('/(\.\.\.error\d+\.\.\.)/', $essay, -1, PREG_SPLIT_DELIM_CAPTURE);
        // dd($splitEssay);
        // Thêm từng phần của đoạn văn và các comment tương ứng
        foreach ($splitEssay as $part) {
            if (preg_match('/error(\d+)/', $part, $matches)) {
                $errorIndex = (int)$matches[1] - 1; // Lấy chỉ mục lỗi tương ứng trong mảng $errors
                if (isset($errors[$errorIndex])) {
                    $errorText = $errors[$errorIndex]['error'];
                    $commentText = $errors[$errorIndex]['comment'];

                    // Tạo TextRun để thêm lỗi và comment
                    $textRun = $section->addTextRun();

                    // Tạo comment cho lỗi
                    $comment = new Comment('Editor', new \DateTime());
                    $comment->addText($commentText);
                    $phpWord->addComment($comment);

                    // Thêm lỗi vào đoạn văn và gắn comment
                    $textError = $textRun->addText($errorText, ['bold' => true]);
                    $textError->setCommentRangeStart($comment);
                    $textError->setCommentRangeEnd($comment);
                }
            } else {
                // Thêm các phần văn bản không phải lỗi
                $section->addText($part);
            }
        }
        $fileName = 'document_with_comments_test2.docx';
        $filePath = storage_path('app/public/' . $fileName);
        $phpWord->save($filePath, 'Word2007');
        return $filePath;
    }

}

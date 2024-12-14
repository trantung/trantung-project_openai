<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $table = "question";
    public $fillable = ['name', 'question', 'answer'];
    public $timestamps = true;

    public static function criterionCoherenceCohesion()
    {
        return "Criterion 'Organize Information logically with clear progression throughout the response.': \n -If the prompt can be followed effortlessly. Cohesion is used in such a way that it very rarely attracts attention, the band=9 \n If the prompt can be followed with ease.Information and ideas are logically sequenced, and cohesion is well managed, the band=8\n-If information and ideas are logically organised, and there is a clear progression throughout the response. (A few lapses may occur, but these are minor.), the band=7\n-If Information and ideas are generally arranged coherently and there is a clear overall progression, the band = 6\n-If the maOrganisation is evident but is not wholly logical and there may be a lack of overall progression. Nevertheless, there is a sense of underlying coherence to the response. The relationship of ideas can be followed but the sentences are not fluently linked to each other, the band=5\n-If Information and ideas are evident but not arranged coherently and there is no clear progression within the response. Relationships between ideas can be unclear and/or inadequately marked, the band=4\n-If There is no apparent logical organisation. Ideas are discernible but difficult to relate to each other, the band=3\n-If There is little relevant message, or the entire response may be off-topic, the band=2\n-If Responses of 20 words or fewer are rated at Band 1, the band=1\nCriterion 'Use cohesive devices including reference and substitution .':\n -If Cohesion is used in such a way that it very rarely attracts attention. Any lapses in coherence or cohesion are minimal, the band=9\n -If Occasional lapses in coherence and cohesion may occur, the band=8\n -If A range of cohesive devices including reference and substitution is used flexibly but with some inaccuracies or some over/under use,the band=7\n -If Cohesive devices are used to some good effect but cohesion within and/or between sentences may be faulty or mechanical due to misuse, overuse or omission. The use of reference and substitution may lack flexibility or clarity and result in some repetition or error, the band=6\n -IfThere may be limited/overuse of cohesive devices with some inaccuracy. The writing may be repetitive due to inadequate and/or inaccurate use of reference and substitution,the band=5\n -If There is some use of basic cohesive devices, which may be inaccurate or repetitive. There is inaccurate use or a lack of substitution or referencing,the band=4\n -If There is minimal use of sequencers or cohesive devices. Those used do not necessarily indicate a logical relationship between ideas. There is difficulty in identifying referencing,the band=3\n -If There is little relevant message, or the entire response may be off-topic. There is little evidence of control of organisational features,the band=2\n -If responses of 20 words or fewer are rated at Band 1 and The content is wholly unrelated to the prompt,the band=1\nCriterion 'Paragraphing.':\n -If Paragraphing is skilfully managed, the band=9\n -If Paragraphing is used sufficiently and appropriately, the band=8\n -If Paragraphing is generally used effectively to support overall coherence, and the sequencing of ideas within a paragraph is generally logical, the band=7\n -If Paragraphing may not always be logical and/or the central topic may not always be clear, the band=6\n -If Paragraphing may be inadequate or missing, the band=5\n -If There may be no paragraphing and/or no clear main topic within paragraphs, the band=4\n -If Any attempts at paragraphing are unhelpful, the band=3\n -If There is little evidence of control of organisational features, the band=2\n -If responses of 20 words or fewer are rated at Band 1 and the content is wholly unrelated to the prompt, the band=1";
    }

    public static function criterionTaskResponse()
    {
        return "Criterion 'Address all parts of the question.': \n -If the prompt is appropriately addressed and explored in depth, the band=9 \n If the prompt is appropriately and sufficiently addressed, the band=8\n-If the main parts of the prompt are appropriately addressed, the band=7\n-If the main parts of the prompt are addressed (though some may be more fully covered than others) and an appropriate format is used, the band = 6\n-If the main parts of the prompt are incompletely addressed and the format may be inappropriate in places, the band=5\n-If the prompt is tackled in a minimal way, or the answer is tangential, possibly due to some misunderstanding of the prompt and the format may be inappropriate, the band=4\n-If No part of the prompt is adequately addressed, or the prompt has been misunderstood, the band=3\n-If the content is barely related to the prompt, the band=2\n-If responses of 20 words or fewer are rated at Band 1 and the content is wholly unrelated to the prompt, the band=1\nCriterion 'Present a clear and developed position throughout.':\n -If a clear and fully developed position is presented which directly answers the question/s, the band=9\n -If a clear and well-developed position is presented in response to the question/s, the band=8\n -If aclear and developed position is presented,the bugand=7\n -If a position is presented that is directly relevant to the prompt,althoh the conclusions drawn may be unclear, unjustified or repetitive, the band=6\n -If the writer expresses a position, but the development is not always clear,the band=5\n -If a position is discernible, but the reader has to read carefully to find it,the band=4\n -If no relevant position can be identified, and/or there is little direct response to the question/s,the band=3\n -If no position can be identified,the band=2\n -If responses of 20 words or fewer are rated at Band 1 and The content is wholly unrelated to the prompt,the band=1\nCriterion 'Present, develop, support ideas.':\n -If Ideas are relevant, fully extended and well supported.Any lapses in content or support are extremely rare, the band=9\n -If Ideas are relevant, well extended and supported.There may be occasional omissions or lapses in content, the band=8\n -If Main ideas are extended and supported but there may be a tendency to over-generalise or there may be a lack of focus and precision in supporting ideas/material, the band=7\n -If Main ideas are relevant, but some may be insufficiently developed or may lack clarity, while some supporting arguments and evidence may be less relevant or inadequate, the band=6\n -If Some main ideas are put forward, but they are limited and are not sufficiently developed and/or there may be irrelevant detail. There may be some repetition, the band=5\n -If Main ideas are difficult to identify and such ideas that are identifiable may lack relevance, clarity and or support. Large parts of the response may be repetitive, the band=4\n -If There are few ideas, and these may be irrelevant or insufficiently developed, the band=3\n -If There may be glimpses of one or two ideas without development, the band=2\n -If responses of 20 words or fewer are rated at Band 1 and the content is wholly unrelated to the prompt, the band=1";
    }
    
    public static function criterionLexicalResource()
    {
        return "Criterion 'Use a wide range of vocabulary.': \n-If Full flexibility and precise use are widely evident, the band=9 \n-If A wide resource is fluently and flexibly used to convey precise meanings, the band=8\n-If The resource is sufficient to allow some flexibility and precision, the band=7\n-If The resource is generally adequate and appropriate for the task, the band = 6\n-If The resource is limited but minimally adequate for the task, the band=5\n-If The resource is limited and inadequate for or unrelated to the task and Vocabulary is basic and may be used repetitively, the band=4\n-If The resource is inadequate (which may be due to the response being significantly underlength) and Possible over-dependence on input material or memorised language, the band=3\n-If The resource is extremely limited with few recognisable strings, apart from memorised phrases, the band=2\n-If Responses of 20 words or fewer are rated at band 1. No resource is apparent, except for a few isolated words,the band=1\n Criterion 'Use the words accurately.':\n -If A wide range of vocabulary is used accurately and appropriately with very natural and sophisticated control of lexical features, the band=9\n -If There is skilful use of uncommon and/or idiomatic items when appropriate, despite occasional inaccuracies in word choice and collocation, the band=8\n -If There is some ability to use less common and/or idiomatic items. An awareness of style and collocation is evident, though inappropriacies occur,the band=7\n -If The meaning is generally clear in spite of a rather restricted range or a lack of precision in word choice. If the writer is a risk-taker, there will be a wider range of vocabulary used but higher degrees of inaccuracy or inappropriacy, the band=6\n -If Simple vocabulary may be used accurately but the range does not permit much variation in expression. There may be frequent lapses in the appropriacy of word choice and a lack of flexibility is apparent in frequent simplifications and/or repetitions,the band=5\n -If There may be inappropriate use of lexical chunks (e.g. memorised phrases, formulaic language and/or language from the input material),the band=4\n -If Control of word choice and/or spelling is very limited, and errors predominate. These errors may severely impede meaning,the band=3\n -If There is no apparent control of word formation and/or spelling,the band=2\n -If Responses of 20 words or fewer are rated at band 1. No resource is apparent, except for a few isolated words,the band=1\n Criterion 'Use correct spelling and word formation.':\n -If Minor errors in spelling and word formation are extremely rare and have minimal impact on communication, the band=9\n -If Occasional errors in spelling and/or word formation may occur, but have minimal impact on communication, the band=8\n -If There are only a few errors in spelling and/or word formation and they do not detract from overall clarity, the band=7\n -If There are some errors in spelling and/or word formation, but these do not impede communication, the band=6\n -If Errors in spelling and/or word formation may be noticeable and may cause some difficulty for the reader, the band=5\n -If Inappropriate word choice and/or errors in word formation and/or in spelling may impede meaning, the band=4\n -If Control of word choice and/or spelling is very limited, and errors predominate. These errors may severely impede meaning, the band=3\n -If There is no apparent control of word formation and/or spelling., the band=2\n -If Responses of 20 words or fewer are rated at band 1. No resource is apparent, except for a few isolated words,the band=1\n";
    }

    public static function criterionGramma()
    {
        return "Criterion 'Use a wide range of structures.': \n -If A wide range of structures is used with full flexibility and control, the band=9 \n -If A wide range of structures is flexibly and accurately used, the band=8\n -If A variety of complex structures is used with some flexibility and accuracy, the band=7\n -If A mix of simple and complex sentence forms is used but flexibility is limited, the band = 6\n -If The range of structures is limited and rather repetitive, the band=5\n -If A very limited range of structures is used. Subordinate clauses are rare and simple sentences predominate, the band=4\n -If Sentence forms are attempted, but errors in grammar and punctuation predominate (except in memorised phrases or those taken from the input material). This prevents most meaning from coming through., the band=3\n -If There is little or no evidence of sentence forms (except in memorised phrases), the band=2\n -If Responses of 20 words or fewer are rated ,the band=1\n Criterion 'Use grammar and punctuation accurately.':\n -If Punctuation and grammar are used appropriately throughout, the band=9\n -If The majority of sentences are error-free, and punctuation is well managed, the band=8\n -If Grammar and punctuation are generally well controlled, and error-free sentences are frequent,the band=7\n -If Examples of more complex structures are not marked by the same level of accuracy as in simple structures, the band=6\n -If Although complex sentences are attempted, they tend to be faulty, and the greatest accuracy is achieved on simple sentences,the band=5\n -If Some structures are produced accurately but grammatical errors are frequent and may impede meaning,the band=4\n -If Length may be insufficient to provide evidence of control of sentence forms,the band=3\n -If There is little or no evidence of sentence forms (except in memorised phrases),the band=2\n -If Responses of 20 words or fewer are rated at Band 1. No rateable language is evident,the band=1\n Criterion 'Frequency of grammatical errors.':\n -If Minor errors are extremely rare and have minimal impact on communication, the band=9\n -If Occasional, non-systematic errors and inappropriacies occur, but have minimal impact on communication, the band=8\n -If A few errors in grammar may persist, but these do not impede communication, the band=7\n -If Errors in grammar and punctuation occur, but rarely impede communication, the band=6\n -If Grammatical errors may be frequent and cause some difficulty for the reader. Punctuation may be faulty, the band=5\n -If Some structures are produced accurately but grammatical errors are frequent and may impede meaning. Punctuation is often faulty or inadequate, band=4\n -If Length may be insufficient to provide evidence of control of sentence forms, the band=3\n -If There is little or no evidence of sentence forms (except in memorised phrases), the band=2\n -If Responses of 20 words or fewer are rated band 1. No rateable language is evident,the band=1\n";
    }

    public static function task1LexicalResource()
    {
        return "Criterion 'Use a wide range of vocabulary': \n -If Full flexibility and precise use are widely evident, the band=9 \n -If A wide resource is fluently and flexibly used to convey precise meanings, the band=8\n -If The resource is sufficient to allow some flexibility and precision, the band=7\n -If The resource is generally adequate and appropriate for the task, the band = 6\n -If The resource is limited but minimally adequate for the task, the band=5\n -If The resource is limited and inadequate for or unrelated to the task. Vocabulary is basic and may be used repetitively, the band=4\n -If The resource is inadequate (which may be due to the response being significantly underlength). Possible over-dependence on input material or memorised language, the band=3\n -If The resource is extremely limited with few recognisable strings, apart from memorised phrases, the band=2\n -If No resource is apparent, except for a few isolated words ,the band=1\n Criterion 'Use the words accurately':\n -If A wide range of vocabulary is used accurately and appropriately with very natural and sophisticated control of lexical features, the band=9\n -If There is skilful use of uncommon and/or idiomatic items when appropriate, despite occasional inaccuracies in word choice and collocation, the band=8\n -If There is some ability to use less common and/or idiomatic items. An awareness of style and collocation is evident, though inappropriacies occur,the band=7\n -If The meaning is generally clear in spite of a rather restricted range or a lack of precision in word choice. If the writer is a risk-taker, there will be a wider range of vocabulary used but higher degrees of inaccuracy or inappropriacy, the band=6\n -If Simple vocabulary may be used accurately but the range does not permit much variation in expression. There may be frequent lapses in the appropriacy of word choice and a lack of flexibility is apparent in frequent simplifications and/or repetitions,the band=5\n -If There may be inappropriate use of lexical chunks (e.g. memorised phrases, formulaic language and/or language from the input material),the band=4\n -If Control of word choice and/or spelling is very limited, and errors predominate. These errors may severely impede meaning,the band=3\n -If There is no apparent control of word formation and/or spelling. Band 1: Responses of 20 words or fewer are rated at,the band=2\n -If No resource is apparent, except for a few isolated words,the band=1\n Criterion 'Use correct spelling and word formation':\n -If Minor errors in spelling and word formation are extremely rare and have minimal impact on communication, the band=9\n -If Occasional errors in spelling and/or word formation may occur, but have minimal impact on communication, the band=8\n -If There are only a few errors in spelling and/or word formation and they do not detract from overall clarity, the band=7\n -If There are some errors in spelling and/or word formation, but these do not impede communication, the band=6\n -If Errors in spelling and/or word formation may be noticeable and may cause some difficulty for the reader, the band=5\n -If Inappropriate word choice and/or errors in word formation and/or in spelling may impede meaning, band=4\n -If Control of word choice and/or spelling is very limited, and errors predominate. These errors may severely impede meaning, the band=3\n -If There is no apparent control of word formation and/or spelling, the band=2\n -If Responses of 20 words or fewer are rated at Band 1. No resource is apparent, except for a few isolated words,the band=1\n";
    }

    public static function task1TaskAchievement()
    {
        return "Criterion 'Address the requirements of the task': \n -If  All the requirements of the task are fully and appropriately satisfied. There may be extremely rare lapses in content, the band=9 \n -If The response covers all the requirements of the task appropriately, relevantly and sufficiently., the band=8\n -If The response covers the requirements of the task. The content is relevant and accurate, there may be a few omissions or lapses.The format is appropriate, the band=7\n -If The response focuses on the requirements of the task and an appropriate format is used, the band = 6\n -If The response generally addresses the requirements of the task. The format may be inappropriate in places, the band=5\n -If The response is an attempt to address the task, the band=4\n -If The response does not address the requirements of the task (possibly because of misunderstanding of the data/diagram/situation), the band=3\n -If The content barely relates to the task, the band=2\n -If Responses of 20 words or fewer are rated at Band 1. The content is wholly unrelated to the task. Any copied rubric must be discounted,the band=1 \n Criterion 'Present a clear overview and accurate key features':\n -If All the requirements of the task are fully and appropriately satisfied. There may be extremely rare lapses in content, the band=9\n -If  Key features are skilfully selected, and clearly presented, highlighted and illustrated. There may be occasional omissions or lapses in content, the band=8\n -If The content is relevant and accurate, there may be a few omissions or lapses. The format is appropriate, though inappropriacies occur,the band=7\n -If Some irrelevant, inappropriate or inaccurate information may occur in areas of detail or when illustrating or extending the main points. Some details may be missing (or excessive) and further extension or illustration may be needed, the band=6\n -If There may be a tendency to focus on details (without referring to the bigger picture). The inclusion of irrelevant, inappropriate or inaccurate material in key areas detracts from the task achievement. There is limited detail when extending and illustrating the main points,the band=5\n -If The format may be inappropriate. Key features which are presented may be irrelevant, repetitive, inaccurate or inappropriate,the band=4\n -If Limited information is presented, and this may be used repetitively,the band=3\n -If The content barely relates to the task,the band=2\n -If Responses of 20 words or fewer are rated at Band 1. The content is wholly unrelated to the task. Any copied rubric must be discounted.,the band=1 \n Criterion 'Present relevant information, appropriate format':\n -If All the requirements of the task are fully and appropriately satisfied. There may be extremely rare lapses in content, the band=9\n -If Key features are skilfully selected, and clearly presented, highlighted and illustrated. There may be occasional omissions or lapses in content, the band=8\n -If The content is relevant and accurate, there may be a few omissions or lapses. The format is appropriate, the band=7\n -If Some irrelevant, inappropriate or inaccurate information may occur in areas of detail or when illustrating or extending the main points. Some details may be missing (or excessive) and further extension or illustration may be needed, the band=6\n -If There may be a tendency to focus on details (without referring to the bigger picture). The inclusion of irrelevant, inappropriate or inaccurate material in key areas detracts from the task achievement. There is limited detail when extending and illustrating the main points, the band=5\n -If The format may be inappropriate. Key features which are presented may be irrelevant, repetitive, inaccurate or inappropriate, band=4\n -If Limited information is presented, and this may be used repetitively, the band=3\n -If The content barely relates to the task, the band=2\n -If Responses of 20 words or fewer are rated at Band 1. The content is wholly unrelated to the task. Any copied rubric must be discounted,the band=1 \n";
    }

    public static function task1CoherenceCohesion()
    {
        return "Criterion 'Organize Information logically with clear progression throughout the response': \n -If The message can be followed effortlessly. Cohesion is used in such a way that it very rarely attracts attention, the band=9 \n -If The message can be followed with ease.Information and ideas are logically sequenced, and cohesion is well managed, the band=8\n -If Information and ideas are logically organised, and there is a clear progression throughout the response. A few lapses may occur, but these are minor, the band =7\n -If Information and ideas are generally arranged coherently and there is a clear overall progression, the band = 6\n -If Organisation is evident but is not wholly logical and there may be a lack of overall progression. Nevertheless, there is a sense of underlying coherence to the response. The relationship of ideas can be followed but the sentences are not fluently linked to each other, the band = 5\n -If Information and ideas are evident but not arranged coherently and there is no clear progression within the response. Relationships between ideas can be unclear and/or inadequately marked, the band=4\n -If There is no apparent logical organisation. Ideas are discernible but difficult to relate to each other, the band=3\n -If There is little relevant message, or the entire response may be off-topic, the band=2\n -If  Responses of 20 words or fewer are rated at Band 1. The writing fails to communicate any message and appears to be by a virtual non-writer,the band=1 \nCriterion 'Use cohesive devices including reference and substitution':\n -If Cohesion is used in such a way that it very rarely attracts attention. Any lapses in coherence or cohesion are minimal, the band=9\n -If Occasional lapses in coherence and cohesion may occur, the band=8\n -If  A range of cohesive devices including reference and substitution is used flexibly but with some inaccuracies or some over/under use,the band = 7\n -If Cohesive devices are used to some good effect but cohesion within and/or between sentences may be faulty or mechanical due to misuse, overuse or omission. The use of reference and substitution may lack flexibility or clarity and result in some repetition or error, the band=6\n -If There may be limited/overuse of cohesive devices with some inaccuracy. The writing may be repetitive due to inadequate and/or inaccurate use of reference and substitution,the band=5\n -If There is some use of basic cohesive devices, which may be inaccurate or repetitive. There is inaccurate use or a lack of substitution or referencing,the band=4\n -If There is minimal use of sequencers or cohesive devices. Those used do not necessarily indicate a logical relationship between ideas. There is difficulty in identifying referencing,the band=3\n -If There is little relevant message, or the entire response may be off-topic. There is little evidence of control of organisational features,the band=2\n -If Responses of 20 words or fewer are rated at Band 1. The writing fails to communicate any message and appears to be by a virtual non-writer,the band=1 \n Criterion 'Paragraphing':\n -If Paragraphing is skilfully managed, the band=9\n -If Paragraphing is used sufficiently and appropriately, the band=8\n -If Paragraphing is generally used effectively to support overall coherence, and the sequencing of ideas within a paragraph is generally logical, the band=7\n -If Paragraphing may not always be logical and/or the central topic may not always be clear, the band=6\n -If  Paragraphing may be inadequate or missing, the band=5\n -If The format may be inappropriate. Key features which are presented may be irrelevant, repetitive, inaccurate or inappropriate, band=4\n -If There may be no paragraphing and/or no clear key features, information within paragraphs, the band=3\n -If There is little evidence of control of organisational features, the band=2\n -If Responses of 20 words or fewer are rated at Band 1. The writing fails to communicate any message and appears to be by a virtual non-writer,the band=1 \n";
    }

    public static function task1Gramma()
    {
        return "Criterion 'Use a wide range of structures': \n -If A wide range of structures is used with full flexibility and control, the band=9 \n -If A wide range of structures is flexibly and accurately used, the band=8\n -If A variety of complex structures is used with some flexibility and accuracy, the band =7\n -If A mix of simple and complex sentence forms is used but flexibility is limited, the band = 6\n -If The range of structures is limited and rather repetitive, the band = 5\n -If A very limited range of structures is used. Subordinate clauses are rare and simple sentences predominate, the band=4\n -If Sentence forms are attempted, but errors in grammar and punctuation predominate (except in memorised phrases or those taken from the input material). This prevents most meaning from coming through, the band=3\n -If There is little or no evidence of sentence forms (except in memorised phrases)., the band=2\n -If Responses of 20 words or fewer are rated at Band 1,the band=1\n Criterion 'Use grammar and punctuation accurately':\n -If  Punctuation and grammar are used appropriately throughout, the band=9\n -If The majority of sentences are error-free, and punctuation is well managed, the band=8\n -If Grammar and punctuation are generally well controlled, and error-free sentences are frequent,the band = 7\n -If Examples of more complex structures are not marked by the same level of accuracy as in simple structures, the band=6\n -If Although complex sentences are attempted, they tend to be faulty, and the greatest accuracy is achieved on simple sentences,the band=5\n -If Some structures are produced accurately but grammatical errors are frequent and may impede meaning,the band=4\n -If Length may be insufficient to provide evidence of control of sentence forms.,the band=3\n -If There is little or no evidence of sentence forms (except in memorised phrases),the band=2\n -If Responses of 20 words or fewer are rated at Band 1. No rateable language is evident,the band=1\n Criterion 'Frequency of grammatical errors':\n -If Minor errors are extremely rare and have minimal impact on communication, the band=9\n -If Occasional, non-systematic errors and inappropriacies occur, but have minimal impact on communication, the band=8\n -If A few errors in grammar may persist, but these do not impede communication, the band=7\n -If Errors in grammar and punctuation occur, but rarely impede communication, the band=6\n -If Grammatical errors may be frequent and cause some difficulty for the reader. Punctuation may be faulty, the band=5\n -If Some structures are produced accurately but grammatical errors are frequent and may impede meaning. Punctuation is often faulty or inadequate, band=4\n -If Length may be insufficient to provide evidence of control of sentence forms, the band=3\n -If There is little or no evidence of sentence forms (except in memorised phrases), the band=2\n -If Responses of 20 words or fewer are rated at Band 1. No rateable language is evident,the band=1\n";
    }
}

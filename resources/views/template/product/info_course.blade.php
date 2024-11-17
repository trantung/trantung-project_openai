<form id="info_course" class="hide">
    <div class="course-name">
        <h4 id="label_course_name">Tên sản phẩm</h4>
    </div>
    <div class="mb-2">
        <label>Tên sản phẩm</label>
        <input type="text" id="course_fullname" name="course_fullname" value="Nhập tên sản phẩm">
    </div>
    <div class="mb-2">
        <label>Mã sản phẩm</label>
        <input type="text" id="course_code" name="course_code" placeholder="Nhập mã sản phẩm">
    </div>
    <div class="mb-2">
        <label>Thư mục cha</label>
        <select id="course_parent" name="course_parent" style="width: 100%;">
                <option value="177">IELTS Tutoring - Introduction</option>
                <option value="204">Sản phẩm 115</option>
                <option value="197">Sản phẩm 106</option>
                <option value="196">Sản phẩm 105</option>
                <option value="195">Sản phẩm 104</option>
                <option value="180">IELTS Tutoring - Foundation</option>
                <option value="179">IELTS Tutoring - Preparation</option>
                <option value="192">Sản phẩm 100</option>
                <option value="178">IELTS Tutoring - Intensive</option>
                <option value="152">🏫IELTS Classroom - Introduction</option>
                <option value="193">Sản phẩm 102</option>
                <option value="149">🏫IELTS Classroom - Foundation</option>
                <option value="151">🏫IELTS Classroom - Preparation</option>
                <option value="150">🏫IELTS Classroom - Intensive</option>
                <option value="153">🏫Classroom - IELTS TEST</option>
                <option value="183">📋 IELTS Classroom - Introduction (NEW)</option>
                <option value="181">📋 IELTS Classroom - Foundation (NEW)</option>
                <option value="182">📋 IELTS Classroom - Preparation (NEW)</option>
                <option value="184">📋 IELTS Classroom - Intensive (NEW)</option>
                <option value="186">💯 IELTS Master 2.0</option>
                <option value="164">Prepare Classroom - Beginners </option>
                <option value="165">Prepare Classroom - Elementary</option>
                <option value="166">Prepare Classroom - Pre - Intermediate</option>
                <option value="167">Prepare Classroom - Intermediate</option>
                <option value="173">Prepare Tutoring - Beginners</option>
                <option value="174">Prepare Tutoring - Elementary</option>
                <option value="175">Prepare Tutoring - Pre-Intermediate</option>
                <option value="176">Prepare Tutoring - Intermediate</option>
                <option value="170">Easy SPEAK - Level 1</option>
                <option value="172">Easy SPEAK - Level 2</option>
                <option value="185">Easy SPEAK - Level 3</option>
                <option value="188">Easy SPEAK for kids</option>
                <option value="190">Kid's Box 1-X</option>
                <option value="171">Interactive Book</option>
                <option value="169">THƯ MỤC CHỨA KHÓA TEST</option>
                <option value="168">TEST DẤU CÂU</option>
                <option value="155">Talk to me - Intro</option>
                <option value="157">Talk to me - Preparation</option>
                <option value="156"> Talk To Me - Foundation</option>
                <option value="154">LCAT - Intensive</option>
                <option value="162">E4TEEN - ELEMENTARY</option>
                <option value="163">E4TEEN - PRE-INTERMEDIATE</option>
                <option value="158">PP-TTM-BEGINNERS</option>
                <option value="159">PP-TTM-ELEMENTARY</option>
                <option value="160">PP-TTM-PRE-INTERMEDIATE</option>
                <option value="191">🏆IELTS Champion</option>
                <option value="161">PP-TTM-INTERMEDIATE</option>
                <option value="198">Product 107</option>
                <option value="200">Sản phẩm 109</option>
                <option value="202">Sản phẩm 111</option>
                <option value="203">Sản phẩm 114</option>
                <option value="206">Sản phẩm 118</option>
                <option value="208">Sản phẩm 120</option>
        </select>
    </div>
    <div class="mb-2">
        <label>Phiên bản</label>
        <input type="text" id="course_version" name="course_version" value="" disabled="">
    </div>
    <div class="mb-2">
        <label>Tên rút gọn</label>
        <input type="text" id="course_shortname" name="course_shortname" placeholder="Nhập tên rút gọn của sản phẩm">
    </div>
    <div class="mb-2">
        <label>Hình ảnh sản phẩm</label>
        <div style="text-align: right">
            <i class="btn-edit-image fa fa-edit" data-type="course" title="Tải ảnh mới" aria-hidden="true"></i>
        </div>
        <div style="text-align: center">
            <img id="course_image" class="hidden" style="max-width: 200px; height: auto; border: 1px solid #dadada; border-radius: 4px;" src="https://saigonctt.com.vn/wp-content/uploads/2022/03/Python_programming_language-Logo.wine_.png">
        </div>
        <div class="course-upload-image"></div>
    </div>
    <div class="mb-2">
        <label>Sapo</label>
        <input type="text" id="course_sapo" name="course_sapo" placeholder="Nhập mô tả">
    </div>
    <div class="mb-2">
        <label>Mô tả sản phẩm</label>
        <textarea id="course_summary" name="course_summary" placeholder="Nhập mô tả" rows="3"></textarea>
    </div>
    <div class="mb-2">
        <label>Trạng thái</label>
        <select id="course_released" name="course_released" style="width: 100%;">
                <option value="0">Dừng phát hành</option>
                <option value="1">Đang phát hành</option>
        </select>
    </div>
    <div class="mb-2">
        <label>Ngày bắt đầu khoá học</label>
        <div style="display: flex;">
            <input type="text" id="course_startdate" class="search-date search-date-startdate" placeholder="--/--/----" value="">
            <a name="calendar-time-startdate-date" class="calendar calendar-time-startdate-date">
                <i class="icon fa fa-calendar fa-fw"></i>
            </a>
            <select class="ml-2" id="course_starthour" name="course_starthour">
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
            </select>
            <div class="ml-1 mr-1">:</div>
            <select id="course_startminute" name="course_startminute">
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                    <option value="32">32</option>
                    <option value="33">33</option>
                    <option value="34">34</option>
                    <option value="35">35</option>
                    <option value="36">36</option>
                    <option value="37">37</option>
                    <option value="38">38</option>
                    <option value="39">39</option>
                    <option value="40">40</option>
                    <option value="41">41</option>
                    <option value="42">42</option>
                    <option value="43">43</option>
                    <option value="44">44</option>
                    <option value="45">45</option>
                    <option value="46">46</option>
                    <option value="47">47</option>
                    <option value="48">48</option>
                    <option value="49">49</option>
                    <option value="50">50</option>
                    <option value="51">51</option>
                    <option value="52">52</option>
                    <option value="53">53</option>
                    <option value="54">54</option>
                    <option value="55">55</option>
                    <option value="56">56</option>
                    <option value="57">57</option>
                    <option value="58">58</option>
                    <option value="59">59</option>
            </select>
        </div>
    </div>
    <div class="mb-2">
        <label>Ngày kết thúc khóa học</label>
        <div style="display: flex;">
            <input type="text" id="course_enddate" class="search-date search-date-enddate" placeholder="--/--/----" value="">
            <a name="calendar-time-enddate-date" class="calendar calendar-time-enddate-date">
                <i class="icon fa fa-calendar fa-fw"></i>
            </a>
            <select class="ml-2" id="course_endhour" name="course_endhour">
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
            </select>
            <div class="ml-1 mr-1">:</div>
            <select id="course_endminute" name="course_endminute">
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                    <option value="32">32</option>
                    <option value="33">33</option>
                    <option value="34">34</option>
                    <option value="35">35</option>
                    <option value="36">36</option>
                    <option value="37">37</option>
                    <option value="38">38</option>
                    <option value="39">39</option>
                    <option value="40">40</option>
                    <option value="41">41</option>
                    <option value="42">42</option>
                    <option value="43">43</option>
                    <option value="44">44</option>
                    <option value="45">45</option>
                    <option value="46">46</option>
                    <option value="47">47</option>
                    <option value="48">48</option>
                    <option value="49">49</option>
                    <option value="50">50</option>
                    <option value="51">51</option>
                    <option value="52">52</option>
                    <option value="53">53</option>
                    <option value="54">54</option>
                    <option value="55">55</option>
                    <option value="56">56</option>
                    <option value="57">57</option>
                    <option value="58">58</option>
                    <option value="59">59</option>
            </select>
        </div>
    </div>
    <div class="mb-2">
        <label>Định dạng</label>
        <select id="course_format" name="course_format" style="width: 100%;">
                <option value="singleactivity">Định dạng hoạt động đơn lẻ</option>
                <option value="social">Định dạng kiểu diễn đàn cộng đồng</option>
                <option value="topics">Định dạng chủ đề</option>
                <option value="weeks">Định dạng theo tuần</option>
        </select>
    </div>
    <div class="mb-2">
        <label>Bắt buộc ngôn ngữ</label>
        <select id="course_lang" name="course_lang" style="width: 100%;">
                <option value="">Không bắt buộc</option>
                <option value="en">English ‎(en)‎</option>
                <option value="vi">Tiếng Việt ‎(vi)‎</option>
        </select>
    </div>
    <div class="mb-2">
        <label>Dung lượng tối đa được tải lên</label>
        <select id="course_maxbytes" name="course_maxbytes" style="width: 100%;">
                <option value="0">Hệ thống giới hạn đăng tải (250MB)</option>
                <option value="262144000">250MB</option>
                <option value="104857600">100MB</option>
                <option value="52428800">50MB</option>
                <option value="20971520">20MB</option>
                <option value="10485760">10MB</option>
                <option value="5242880">5MB</option>
                <option value="2097152">2MB</option>
                <option value="1048576">1MB</option>
                <option value="512000">500KB</option>
                <option value="102400">100KB</option>
                <option value="51200">50KB</option>
                <option value="10240">10KB</option>
        </select>
    </div>
    <div class="mt-4" style="text-align: center">
        <a class="btn btn-primary btn-save-course">Cập nhật</a>
    </div>
</form>

<script>
    let imageFormLoadTrigger = false;
    $(document).on('click', '.btn-edit-image.fa-edit', function(){
        const $button = $(this); // Lưu trữ tham chiếu đến nút bấm
        if(!imageFormLoadTrigger){
            $.get('{{ route("load.image.form") }}', function (htmlContent) {
                $button.removeClass('fa-edit');
                $button.addClass('fa-undo');
                $('#info_course #course_image').addClass('hidden');
                $('.course-upload-image').html(htmlContent);
                imageFormLoadTrigger = true;
            }).fail(function (error) {
                console.error('Error loading form:', error);
            });
        }else{
            $('#info_course #course_image').addClass('hidden');
        }
    });

    $(document).on('click', '.btn-edit-image.fa-undo', function(){
        const $button = $(this); // Lưu trữ tham chiếu đến nút bấm
        $button.removeClass('fa-undo');
        $button.addClass('fa-edit');
        $('#info_course #course_image').removeClass('hidden');
        // $('.course-upload-image').empty(); // Giả sử bạn muốn xóa nội dung chứ không phải khôi phục htmlContent
    });

</script>

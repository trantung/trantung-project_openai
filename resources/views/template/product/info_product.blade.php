<form id="info_product">
    <div class="product-name">
        <h4 id="label_product_name">Tên sản phẩm</h4>
    </div>
    <div class="mb-2">
        <label>Tên sản phẩm</label>
        <input type="text" id="product_name" name="product_name" value="Nhập tên sản phẩm">
    </div>
    <div class="mb-2">
        <label>Mã sản phẩm</label>
        <input type="text" id="product_code" name="product_code" placeholder="Nhập mã sản phẩm">
    </div>

    <div class="mb-2 product_parent-box">
        <label>Thư mục cha</label>
        <select id="product_parent" name="product_parent" style="width: 100%;">
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
        <label>Hình ảnh sản phẩm</label>
        <div style="text-align: right">
            <i class="btn-edit-image fa fa-edit" data-type="product" title="Tải ảnh mới" aria-hidden="true"></i>
        </div>
        <div style="text-align: center">
            <img id="product_image" class="hidden" style="max-width: 200px; height: auto; border: 1px solid #dadada; border-radius: 4px;" src="https://saigonctt.com.vn/wp-content/uploads/2022/03/Python_programming_language-Logo.wine_.png">
        </div>
        <div class="product-upload-image"></div>
    </div>
    <div class="mb-2">
        <label>Sapo</label>
        <input type="text" id="product_sapo" name="product_sapo" placeholder="Nhập mô tả">
    </div>
    <div class="mb-2">
        <label>Mô tả sản phẩm</label>
        <textarea id="product_description" name="product_description" placeholder="Nhập mô tả" rows="3"></textarea>
    </div>
    <div class="mb-2">
        <label>Người tạo</label>
        <input type="text" id="product_created_by" value="" disabled="">
    </div>
    <div class="mb-2">
        <label>Ngày tạo</label>
        <input type="text" id="product_created_at" value="" disabled="">
    </div>
    <div class="mb-2">
        <label>Người cập nhật</label>
        <input type="text" id="product_updated_by" value="" disabled="">
    </div>
    <div class="mb-2">
        <label>Ngày cập nhật</label>
        <input type="text" id="product_updated_at" value="" disabled="">
    </div>
    <div class="mt-4" style="text-align: center">
        <a class="btn btn-primary btn-save-product">Cập nhật</a>
    </div>
</form>

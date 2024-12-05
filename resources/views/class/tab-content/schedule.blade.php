<div class="tab-pane-content">
    <div class="schedule_condition mt-3 row-search-add">
        <select id="filter-schedule" name="class" style="width: 30%;" class="select2-hidden-accessible" aria-hidden="true">
            <option value="" disabled selected>Nhập tên sự kiện</option>
        </select>
        <select class="schedule_status" name="schedule_status">
            <option value="">Chọn trạng thái buổi học</option>
            <option value="in_progress">Đang học</option>
            <option value="upcoming">Đến giờ</option>
            <option value="planned">Chưa học</option>
            <option value="done">Kết thúc</option>
            <option value="cancelled">Đã huỷ</option>
            <option value="accepted">Nghiệm thu</option>
            <option value="not_accepted">Không đạt</option>
            <option value="stop">Tạm dừng</option>
        </select>
    </div>
    <div class="schedule_button mt-3">

    </div>
    <div class="wrapper-table-scroll schedule_result mt-3">
        <table class="table table-scroll table-schedule-event">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Ngày học</th>
                    <th>Thời gian</th>
                    <th>Tên sự kiện</th>
                    <th>Tên Bài học</th>
                    <th>Nhóm GV</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div>
    <div class="mb-5">
        <div class="text-sm font-semibold text-slate-900">บันทึกรายการใหม่</div>
        <p class="mt-1 text-sm text-slate-500">กรอกข้อมูลปัญหาให้ครบเพื่อให้ทีมตรวจสอบและติดตามงานได้ง่าย</p>
    </div>

    <form wire:submit.prevent="createTicket" class="space-y-6">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">ชื่อผู้แจ้ง</label>
                <input wire:model.defer="requester_name" type="text" class="input-modern" placeholder="กรอกชื่อผู้แจ้ง" />
                @error('requester_name') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">อีเมลผู้แจ้ง</label>
                <input wire:model.defer="requester_email" type="email" class="input-modern" placeholder="name@hospital.go.th" />
                @error('requester_email') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">แผนก</label>
                <input wire:model.defer="department_name" list="master-departments" type="text" class="input-modern" placeholder="เลือกหรือพิมพ์ค้นหาแผนกจาก master" />
                <datalist id="master-departments">
                    @foreach ($departments as $department)
                        <option value="{{ $department->name }}"></option>
                    @endforeach
                </datalist>
                @error('department_name') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">ตำแหน่งผู้แจ้ง</label>
                <input wire:model.defer="requester_position" list="master-requester-positions" type="text" class="input-modern" placeholder="เลือกหรือพิมพ์ค้นหาตำแหน่งจาก master" />
                <datalist id="master-requester-positions">
                    @foreach ($positions as $position)
                        <option value="{{ $position->name }}"></option>
                    @endforeach
                </datalist>
                @error('requester_position') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">เบอร์ติดต่อ</label>
                <input wire:model.defer="contact_phone" type="text" class="input-modern" placeholder="กรอกเบอร์ติดต่อ" />
                @error('contact_phone') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">สถานที่</label>
                <input wire:model.defer="location" list="master-locations" type="text" class="input-modern" placeholder="เลือกหรือพิมพ์ค้นหาสถานที่จาก master" />
                <datalist id="master-locations">
                    @foreach ($locations as $location)
                        <option value="{{ $location->name }}"></option>
                    @endforeach
                </datalist>
                @error('location') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">หัวข้อเรื่อง</label>
            <input wire:model.defer="subject" type="text" class="input-modern" placeholder="เช่น เข้าใช้งาน HIS ไม่ได้ที่หอผู้ป่วย" />
            @error('subject') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">รายละเอียด</label>
            <input id="ticket_description" type="hidden" wire:model.defer="description" />
            <trix-editor input="ticket_description" class="trix-editor rounded-xl border border-slate-200 bg-white shadow-sm"></trix-editor>
            @error('description') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">หมวดหมู่</label>
                <input wire:model.defer="category" list="master-ticket-categories" type="text" class="input-modern" placeholder="เลือกหรือพิมพ์ค้นหาหมวดหมู่งานจาก master" />
                <datalist id="master-ticket-categories">
                    @foreach ($categories as $category)
                        <option value="{{ $category->name }}"></option>
                    @endforeach
                </datalist>
                @error('category') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">หมวดย่อย</label>
                <input wire:model.defer="subcategory" list="master-ticket-subcategories" type="text" class="input-modern" placeholder="เลือกหรือพิมพ์ค้นหาหมวดงานย่อยจาก master" />
                <datalist id="master-ticket-subcategories">
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->name }}"></option>
                    @endforeach
                </datalist>
                @error('subcategory') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">ประเภทปัญหา</label>
                <input wire:model.defer="issue_type" list="master-ticket-issue-types" type="text" class="input-modern" placeholder="เลือกหรือพิมพ์ค้นหาประเภทปัญหาจาก master" />
                <datalist id="master-ticket-issue-types">
                    @foreach ($issueTypes as $issueType)
                        <option value="{{ $issueType->name }}"></option>
                    @endforeach
                </datalist>
                @error('issue_type') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">ระบบที่ได้รับผลกระทบ</label>
                <input wire:model.defer="affected_system" list="master-ticket-affected-systems" type="text" class="input-modern" placeholder="เลือกหรือพิมพ์ค้นหาระบบที่ได้รับผลกระทบจาก master" />
                <datalist id="master-ticket-affected-systems">
                    @foreach ($affectedSystems as $affectedSystem)
                        <option value="{{ $affectedSystem->name }}"></option>
                    @endforeach
                </datalist>
                @error('affected_system') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Asset Tag</label>
                <input wire:model.defer="asset_tag" type="text" class="input-modern" placeholder="รหัสครุภัณฑ์ / Serial" />
                @error('asset_tag') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">IP Address</label>
                <input wire:model.defer="ip_address" type="text" class="input-modern" placeholder="192.168.x.x" />
                @error('ip_address') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">ความสำคัญ</label>
                <select wire:model.defer="priority" class="select-modern">
                    <option value="low">ต่ำ</option>
                    <option value="medium">ปานกลาง</option>
                    <option value="high">สูง</option>
                    <option value="critical">Critical</option>
                </select>
                @error('priority') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">ระดับผลกระทบ</label>
                <select wire:model.defer="impact_level" class="select-modern">
                    <option value="low">ต่ำ</option>
                    <option value="medium">ปานกลาง</option>
                    <option value="high">สูง</option>
                    <option value="critical">Critical</option>
                </select>
                @error('impact_level') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">วิธีแก้ไข</label>
            <textarea wire:model.defer="resolution" class="textarea-modern" rows="3" placeholder="บันทึกวิธีแก้ไขหรือแนวทางเบื้องต้น"></textarea>
            @error('resolution') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">สาเหตุหลัก</label>
            <textarea wire:model.defer="root_cause" class="textarea-modern" rows="3" placeholder="สรุปสาเหตุหลักของปัญหา"></textarea>
            @error('root_cause') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">ไฟล์แนบ</label>
            <input wire:model="attachments" type="file" multiple class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm" />
            @error('attachments.*') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center gap-2">
            <input wire:model="follow_up_required" id="follow_up_required" type="checkbox" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500" />
            <label for="follow_up_required" class="text-sm text-slate-700">ต้องติดตามงานต่อ</label>
        </div>

        <div class="mt-2 flex items-center justify-between gap-3">
            <div class="text-xs text-slate-500">ระบบจะกำหนดหมายเลข Ticket ให้อัตโนมัติ</div>
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                บันทึก Ticket
            </button>
        </div>
    </form>
</div>

<?php

use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Department;
use App\Models\Employeeinfo;
use Livewire\WithFileUploads;

new class extends Component {

    use WithFileUploads;
    use Toast;
    public $firstname = '';
    public $lastname = '';
    public $selectGender = '';
    public $email = '';
    public $phone = '';
    public $dob = '';
    public $address = '';
    public $emergency_contact = '';
    public $department = '';
    public $departments = [];
    public $employeeID = '';
    public $designation = '';
    public $joiningDate = '';
    public $workLocation = '';
    public $employmentType = '';
    public $employmentTypes = [];
    public $emiratesId;
    public $passport;
    public $workPermit;
    public $certificates;
    public $policeClearance;
    public $medicalCertificate;

    public function mount()
    {
        $this->departments = Department::all();
        $this->employmentTypes = [
            ["id" => 1, "name" => "Full Time"],
            ["id" => 2, "name" => "Part Time"],
            ["id" => 3, "name" => "Contract"]
        ];
    }
    use Toast;

    public function saveEmpInfo()
    {
        $validated = $this->validate([
            'firstname' => 'required|min:2',
            'lastname' => 'required|min:2', 
            'selectGender' => 'required',
            'email' => 'required|email|unique:employeesinfos',
            'phone' => 'required',
            'dob' => 'required|date',
            'address' => 'required',
            'emergency_contact' => 'required',
            'department' => 'required',
            'employeeID' => 'required|unique:employeesinfos,employee_id',
            'designation' => 'required',
            'joiningDate' => 'required|date',
            'workLocation' => 'required',
            'employmentType' => 'required',
            'emiratesId' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'passport' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'workPermit' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'certificates' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'policeClearance' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'medicalCertificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        try {

            $user = new Employeeinfo();
            $user->name = $validated['firstname'];
            $user->lastname = $validated['lastname'];
            $user->gender = $validated['selectGender'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'];
            $user->dob = $validated['dob'];
            $user->address = $validated['address'];
            $user->emergency_contact = $validated['emergency_contact'];
            $user->department_id = $validated['department'];
            $user->employee_id = $validated['employeeID'];
            $user->designation = $validated['designation'];
            $user->joining_date = $validated['joiningDate'];
            $user->work_location = $validated['workLocation'];
            $user->employment_type = $validated['employmentType'];
            
            // Handle file uploads
            $user->emirates_id = $this->emiratesId->store('emirates_id' , 'public');
            $user->passport = $this->passport->store('passport' , 'public');
            $user->work_permit = $this->workPermit->store('work_permit' , 'public');
            $user->certificates = $this->certificates->store('certificates' , 'public');
            $user->police_clearance = $this->policeClearance->store('police_clearance' , 'public');
            $user->medical_certificate = $this->medicalCertificate->store('medical_certificate' , 'public');
            
            $user->save();

            $this->success('Employee added successfully!');
            $this->redirect('/employee/employee-list');
            
        } catch (\Exception $e) {
            $this->error('Error adding employee: ' . $e->getMessage());
        }
    }
};

?>

<div>
    <x-header title="Add Employee" />

    <x-card title="Personal Details" class="mb-6">
        <x-form wire:submit="saveEmpInfo">
            <div class="space-y-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Name Section -->
                    <div class="space-y-4">
                        <x-input 
                            label="First Name" 
                            wire:model="firstname" 
                            placeholder="Enter first name"
                            icon="o-user"
                            hint="As per official documents"
                        />
                        @error('firstname') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="space-y-4">
                        <x-input 
                            label="Last Name" 
                            wire:model="lastname" 
                            placeholder="Enter last name"
                            icon="o-user"
                            hint="As per official documents"
                        />
                        @error('lastname') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="space-y-4">
                        @php
                            $gender = [
                                ["id" => 1, "name" => "Male"],
                                ["id" => 2, "name" => "Female"]
                            ];
                        @endphp
                        <x-select 
                            label="Gender" 
                            :options="$gender" 
                            wire:model="selectGender" 
                            placeholder="Select Gender"
                            icon="o-user"
                            hint="Select your gender"
                        />
                        @error('selectGender') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="space-y-4">
                    <x-input 
                        label="Email Address" 
                        type="email" 
                        wire:model="email" 
                        placeholder="example@company.com"
                        icon="o-envelope"
                        hint="Work email preferred"
                    />
                    @error('email') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="space-y-4">
                    <x-input 
                        label="Phone Number" 
                        type="tel" 
                        wire:model="phone" 
                        placeholder="+971 XX XXX XXXX"
                        icon="o-phone"
                        hint="Include country code"
                    />
                    @error('phone') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="space-y-4">
                    <x-datepicker 
                        label="Date of Birth" 
                        wire:model="dob"
                        icon="o-calendar"
                        hint="Must be 18 or older"
                    />
                    @error('dob') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>
            </div>

            <!-- Additional Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <x-textarea 
                        label="Residential Address" 
                        wire:model="address" 
                        placeholder="Enter your complete residential address..."
                        rows="3"
                        icon="o-home"
                        hint="Include building name, street, and area"
                    />
                    @error('address') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <x-input 
                        label="Emergency Contact" 
                        type="tel" 
                        wire:model="emergency_contact" 
                        placeholder="+971 XX XXX XXXX"
                        icon="o-phone"
                        hint="Different from personal number"
                    />
                    @error('emergency_contact') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>
            </div>
        </div>
        <div class="mt-6 flex items-start bg-blue-50 p-4 rounded-lg">
            <svg class="h-5 w-5 text-blue-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            <p class="ml-3 text-sm text-blue-700">
                Please ensure all information provided matches your official documents.
            </p>
        </div>
        <div class="lg:grid grid-rows-2 grid-cols-3 gap-3">
            <div>
                <x-input 
                    label="Employee ID" 
                    wire:model="employeeID" 
                    placeholder="eg. EMP001"
                />
                @error('employee_id ') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
            <div>
                <x-input 
                    label="Designation" 
                    wire:model="designation" 
                    placeholder="eg. Software Engineer"
                />
                @error('designation') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
            <div>
                <x-select 
                    label="Department" 
                    :options="$departments" 
                    wire:model="department" 
                    placeholder="Select Department"
                    icon="o-briefcase"
                    hint="Select your department"
                />
                @error('department') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
            <div>
                <x-datepicker 
                    label="Joining Date" 
                    wire:model="joiningDate"
                    icon="o-calendar"
                />
                @error('joiningDate') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
            <div>
                <x-input 
                    label="Work Location" 
                    wire:model="workLocation" 
                    placeholder="eg. Bangalore"
                />
                @error('workLocation') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
            <div>
                <x-select 
                    label="Employment Type" 
                    :options="$employmentTypes" 
                    wire:model="employmentType" 
                    placeholder="Select Type"
                />
                @error('employmentType') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
        </div>

        <!-- Information Notice -->
        <div class="mb-4">
            <h3 class="text-lg font-medium text-gray-900">Important Instructions:</h3>
            <ul class="mt-2 text-sm text-gray-600 list-disc list-inside space-y-1">
                <li>All documents should be clear scanned copies</li>
                <li>Accepted formats: PDF, JPG, JPEG, PNG</li>
                <li>Maximum file size: 2MB per document</li>
                <li>Images should be clearly readable</li>
            </ul>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Emirates ID -->
            <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                <div class="font-medium text-gray-900 mb-2">Emirates ID</div>
                <x-file wire:model="emiratesId" accept="image/jpeg,image/png" crop-after-change>
                    <div class="aspect-video flex items-center justify-center bg-white border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 transition-colors duration-200">
                        @if ($emiratesId)
                            <img src="{{ $emiratesId->temporaryUrl() }}" 
                                 class="max-h-40 rounded-lg object-contain" 
                                 alt="Emirates ID Preview" />
                        @else
                            <img src="/images/placeholders/empty-doc.png" 
                                 class="max-h-40 rounded-lg object-contain" 
                                 alt="Emirates ID Preview" />
                        @endif
                    </div>
                </x-file>
                <div class="mt-2 text-sm text-gray-600">Front & back copy required</div>
                @error('emiratesId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Passport -->
            <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                <div class="font-medium text-gray-900 mb-2">Passport with Visa</div>
                <x-file wire:model="passport" accept=".pdf,.jpg,.jpeg,.png" crop-after-change>
                    <div class="aspect-video flex items-center justify-center bg-white border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 transition-colors duration-200">
                        @if ($passport)
                                <img src="{{ $passport->temporaryUrl() }}" 
                                    class="max-h-40 rounded-lg object-contain" 
                                    alt="Emirates ID Preview" />
                        @else
                            <img src="/images/placeholders/empty-doc.png" 
                                    class="max-h-40 rounded-lg object-contain" 
                                    alt="Passport Preview" />
                        @endif
                    </div>
                </x-file>
                <div class="mt-2 text-sm text-gray-600">Include visa page</div>
                @error('passport') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Work Permit -->
            <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                <div class="font-medium text-gray-900 mb-2">UAE Work Permit</div>
                <x-file wire:model="workPermit" accept=".pdf,.jpg,.jpeg,.png" crop-after-change>
                    <div class="aspect-video flex items-center justify-center bg-white border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 transition-colors duration-200">
                        @if ($workPermit)
                            <img src="{{ $workPermit->temporaryUrl() }}" 
                                 class="max-h-40 rounded-lg object-contain" 
                                 alt="Emirates ID Preview" />
                        @else
                            <img src="/images/placeholders/empty-doc.png" 
                                 class="max-h-40 rounded-lg object-contain" 
                                 alt="Work Permit Preview" />
                        @endif
                    </div>
                </x-file>
                <div class="mt-2 text-sm text-gray-600">Valid UAE work permit/visa</div>
                @error('workPermit') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Educational Certificates -->
            <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                <div class="font-medium text-gray-900 mb-2">Educational Certificates</div>
                <x-file wire:model="certificates" accept=".pdf,.jpg,.jpeg,.png" crop-after-change>
                    <div class="aspect-video flex items-center justify-center bg-white border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 transition-colors duration-200">
                         @if ($certificates)
                            <img src="{{ $certificates->temporaryUrl() }}" 
                                 class="max-h-40 rounded-lg object-contain" 
                                 alt="Emirates ID Preview" />
                        @else
                            <img src="/images/placeholders/empty-doc.png" 
                                 class="max-h-40 rounded-lg object-contain" 
                                 alt="Certificates Preview" />
                        @endif
                    </div>
                </x-file>
                <div class="mt-2 text-sm text-gray-600">Attested certificates required</div>
                @error('certificates') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Police Clearance -->
            <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                <div class="font-medium text-gray-900 mb-2">Police Clearance</div>
                <x-file wire:model="policeClearance" accept=".pdf,.jpg,.jpeg,.png" crop-after-change>
                    <div class="aspect-video flex items-center justify-center bg-white border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 transition-colors duration-200">
                    @if ($policeClearance)
                            <img src="{{ $policeClearance->temporaryUrl() }}" 
                                 class="max-h-40 rounded-lg object-contain" 
                                 alt="Police Clearance Preview" />
                        @else
                            <img src="/images/placeholders/empty-doc.png" 
                                 class="max-h-40 rounded-lg object-contain" 
                                 alt="Police Clearance Preview" />
                        @endif
                    </div>
                </x-file>
                <div class="mt-2 text-sm text-gray-600">Recent clearance certificate</div>
                @error('policeClearance') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Medical Certificate -->
            <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                <div class="font-medium text-gray-900 mb-2">Medical Certificate</div>
                <x-file wire:model="medicalCertificate" accept=".pdf,.jpg,.jpeg,.png" crop-after-change>
                    <div class="aspect-video flex items-center justify-center bg-white border-2 border-dashed border-gray-300 rounded-lg hover:border-gray-400 transition-colors duration-200">
                    @if ($medicalCertificate)
                            <img src="{{ $medicalCertificate->temporaryUrl() }}" 
                                 class="max-h-40 rounded-lg object-contain" 
                                 alt="Medical Certificate Preview" />
                        @else
                            <img src="/images/placeholders/empty-doc.png" 
                                 class="max-h-40 rounded-lg object-contain" 
                                 alt="Medical Certificate Preview" />
                        @endif
                    </div>
                </x-file>
                <div class="mt-2 text-sm text-gray-600">Fitness certificate required</div>
                @error('medicalCertificate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mt-6 flex items-start bg-blue-50 p-4 rounded-lg">
            <svg class="h-5 w-5 text-blue-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            <p class="ml-3 text-sm text-blue-700">
                Need help? Contact HR department at <a href="mailto:hr@company.com" class="underline">hr@company.com</a> for assistance with document submission.
            </p>
        </div>


        <x-slot:actions>
    <x-button label="Cancel" />
    <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
    </x-slot:actions>
    
    </x-form>
    </x-card>




</div>
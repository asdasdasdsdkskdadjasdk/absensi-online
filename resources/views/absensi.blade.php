<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Absensi Online') }}
        </h2>
    </x-slot>

    {{-- Elemen tersembunyi untuk menyimpan foto referensi dari controller --}}
    @if($referencePhoto)
        <img id="zahran1.png" src="data:image/jpeg;base64,{{ $referencePhoto }}" class="hidden" alt="Reference Photo"> 
        {{-- <img id="reference-image" src="data:image/jpeg;base64,{{ $referencePhoto }}" class="hidden" alt="Reference Photo">--}}

    @endif

    {{-- --- KARTU PROFIL PENGGUNA --- --}}
    <div class="pt-12 pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center space-x-4">
                        {{-- Foto Karyawan --}}
                        @if($referencePhoto)
                            <img src="data:image/jpeg;base64,{{ $referencePhoto }}" alt="Foto Karyawan" class="w-16 h-16 rounded-full object-cover">
                        @else
                            {{-- Placeholder jika foto tidak ada --}}
                            <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        @endif
                        
                        {{-- Teks Sapaan --}}
                        <div>
                            <h3 class="text-lg font-medium">Selamat Datang, {{ Auth::user()->name }}!</h3>
                            @if(!Auth::user()->is_admin)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $positionName ?? 'Posisi Tidak Ditemukan' }} &mdash; {{ $departmentName ?? 'Departemen Tidak Ditemukan' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- --- KARTU UTAMA ABSENSI --- --}}
    <div class="pt-0 pb-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    
                    <div id="status-area" class="text-center mb-4 p-2 rounded-md">
                        <p id="status-message" class="font-medium"></p>
                    </div>

                    {{-- STEP 1: PILIH TIPE ABSENSI --}}
                    <div id="step-1-type-selection">
                        <p class="text-center font-bold mb-4">Pilih Tipe Absensi Anda</p>
                        <div class="grid grid-cols-1 gap-4">
                            <button id="btn-kantor" class="w-full bg-blue-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-600 transition">Absen Kantor</button>
                            <button id="btn-dinas" class="w-full bg-green-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-600 transition">Absen Dinas Luar</button>
                        </div>
                    </div>

                    {{-- STEP 2: PILIH LOKASI KANTOR --}}
                    <div id="step-2-location-selection" class="hidden mt-6">
                        <label for="office_location_id" class="block font-bold text-center text-gray-700 dark:text-gray-300 mb-2">Pilih Kantor Terkait</label>
                        <select id="office_location_id" class="block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                            <option value="" disabled selected>-- Pilih salah satu --</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" data-lat="{{ $location->latitude }}" data-lon="{{ $location->longitude }}" data-radius="{{ $location->radius_meters }}">
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        <button id="btn-confirm-location" class="mt-4 w-full bg-indigo-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-600 transition">Lanjutkan</button>
                    </div>

                    {{-- STEP 3: TAMPILAN KAMERA & DETEKSI WAJAH --}}
                    <div id="step-3-camera-view" class="hidden mt-6">
                         <div class="relative w-full max-w-md mx-auto mb-4 bg-gray-200 rounded-lg overflow-hidden">
                            <video id="video" width="720" height="560" autoplay muted playsinline class="w-full h-auto transform -scale-x-100"></video>
                        </div>
                    </div>

                    {{-- STEP 4: PREVIEW & KIRIM ABSENSI --}}
                    <div id="step-4-preview-and-submit" class="hidden mt-6">
                        <form id="attendance-form">
                            @csrf
                            <input type="hidden" name="type" id="type">
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                            <input type="hidden" name="office_id" id="office_id">
                            <input type="file" name="photo" id="photo-input" required class="hidden">

                            <div id="preview-container" class="text-center">
                                <p class="font-bold mb-2">Hasil Selfie Anda</p>
                                <img id="selfie-preview" src="" alt="Selfie Preview" class="rounded-lg mb-2 mx-auto border max-h-60 transform -scale-x-100">
                                <div id="selfie-info" class="text-sm bg-gray-100 dark:bg-gray-700 p-3 rounded-lg"></div>
                            </div>
                            
                            <div class="mt-6 flex justify-between items-center">
                                <button type="button" id="btn-retake" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 underline">Ulangi Foto</button>
                                <button type="submit" id="btn-submit" class="bg-red-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-600 transition">Kirim Absen</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL NOTIFIKASI --}}
    <div id="notification-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 h-full w-full flex items-center justify-center z-50">
        <div id="modal-content" class="p-8 border w-96 shadow-lg rounded-md bg-white text-center"></div>
    </div>
    
    <script src="{{ asset('js/dist/face-api.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- DEKLARASI ELEMEN ---
            const step1 = document.getElementById('step-1-type-selection');
            const step2 = document.getElementById('step-2-location-selection');
            const step3_camera = document.getElementById('step-3-camera-view');
            const step4_preview = document.getElementById('step-4-preview-and-submit');
            const statusMessage = document.getElementById('status-message');
            const video = document.getElementById('video');
            let videoStream = null;
            let detectionInterval = null;
            let isProcessing = false;

            const btnKantor = document.getElementById('btn-kantor');
            const btnDinas = document.getElementById('btn-dinas');
            const btnConfirmLocation = document.getElementById('btn-confirm-location');
            const btnRetake = document.getElementById('btn-retake');
            const btnSubmit = document.getElementById('btn-submit');
            const form = document.getElementById('attendance-form');
            const typeInput = document.getElementById('type');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const officeIdInput = document.getElementById('office_id');
            const officeLocationSelect = document.getElementById('office_location_id');
            const photoInput = document.getElementById('photo-input');
            const selfiePreview = document.getElementById('selfie-preview');
            const selfieInfo = document.getElementById('selfie-info');
            const notificationModal = document.getElementById('notification-modal');
            const modalContent = document.getElementById('modal-content');
            const userName = "{{ $user->name }}";
            const referenceImage = document.getElementById('reference-image');
            let referenceDescriptor;
            
            // Variabel Liveness
            const NOD_THRESHOLD = 5;
            const EYE_AR_THRESH = 0.27;

            // Fungsi Bantu
            function updateStatus(message, bgColor, textColor) {
                statusMessage.textContent = message;
                document.getElementById('status-area').className = `text-center mb-4 p-2 rounded-md ${bgColor} ${textColor}`;
            }

            function showModal(isSuccess, message) {
                const icon = isSuccess 
                    ? `<svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-green-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`
                    : `<svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-red-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
                modalContent.innerHTML = `${icon}<h3 class="text-2xl font-bold text-gray-900 mt-4">${isSuccess ? 'Absen Berhasil!' : 'Terjadi Kesalahan'}</h3><p class="text-gray-600 my-2">${message}</p><button id="btn-close-modal" class="mt-4 px-6 py-2 bg-indigo-500 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700">Selesai</button>`;
                notificationModal.classList.remove('hidden');
                document.getElementById('btn-close-modal').addEventListener('click', () => { isSuccess ? window.location.href = '/dashboard' : window.location.reload(); });
            }
            
            function getEyeAspectRatio(eye) {
                const a = Math.hypot(eye[1].x - eye[5].x, eye[1].y - eye[5].y);
                const b = Math.hypot(eye[2].x - eye[4].x, eye[2].y - eye[4].y);
                const c = Math.hypot(eye[0].x - eye[3].x, eye[0].y - eye[3].y);
                return (a + b) / (2.0 * c);
            }

            function haversineDistance(lat1, lon1, lat2, lon2) {
            const R = 6371e3; // Radius bumi dalam meter
            const φ1 = lat1 * Math.PI / 180;
            const φ2 = lat2 * Math.PI / 180;
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lon2 - lon1) * Math.PI / 180;

            const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                      Math.cos(φ1) * Math.cos(φ2) *
                      Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return R * c; // Jarak dalam meter
        }
        
            // Alur Aplikasi
            btnKantor.addEventListener('click', () => handleTypeSelection('kantor'));
            btnDinas.addEventListener('click', () => handleTypeSelection('dinas'));

            function handleTypeSelection(type) {
                typeInput.value = type;
                step1.classList.add('hidden');
                step2.classList.remove('hidden');
            }

            btnConfirmLocation.addEventListener('click', () => {
                if (!officeLocationSelect.value) {
                    alert('Silakan pilih lokasi kantor terlebih dahulu.');
                    return;
                }
                officeIdInput.value = officeLocationSelect.value;
                step2.classList.add('hidden');
                getLocationAndValidate();
            });

            function getLocationAndValidate() {
                updateStatus('Sedang mengambil lokasi Anda...', 'bg-yellow-100', 'text-yellow-700');
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        latitudeInput.value = position.coords.latitude;
                        longitudeInput.value = position.coords.longitude;
                        if (typeInput.value === 'kantor') {
                            const selectedOption = officeLocationSelect.options[officeLocationSelect.selectedIndex];
                            const distance = haversineDistance(position.coords.latitude, position.coords.longitude, parseFloat(selectedOption.dataset.lat), parseFloat(selectedOption.dataset.lon));
                            if (distance > parseInt(selectedOption.dataset.radius, 10)) {
                                updateStatus(`Lokasi Anda di luar jangkauan (jarak ${Math.round(distance)}m).`, 'bg-red-100', 'text-red-700');
                                setTimeout(() => window.location.reload(), 3000);
                                return;
                            }
                        }
                        updateStatus('Lokasi terverifikasi. Silakan hadapkan wajah ke kamera.', 'bg-green-100', 'text-green-700');
                        step3_camera.classList.remove('hidden');
                        startFaceApi();
                    },
                    () => {
                        updateStatus('Gagal mendapatkan lokasi. Pastikan GPS aktif.', 'bg-red-100', 'text-red-700');
                        setTimeout(() => window.location.reload(), 3000);
                    },
                    { enableHighAccuracy: true }
                );
            }

            // Logika Kamera & Face-API
            async function startFaceApi() {
                if (!faceapi.nets.tinyFaceDetector.params) {
                    updateStatus('Memuat model AI, mohon tunggu...', 'bg-blue-100', 'text-blue-700');
                    try {
                        await Promise.all([
                            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                            faceapi.nets.faceExpressionNet.loadFromUri('/models'),
                            faceapi.nets.faceRecognitionNet.loadFromUri('/models')
                        ]);

                        if (referenceImage) {
                            const referenceDetection = await faceapi.detectSingleFace(referenceImage, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
                            if (referenceDetection) {
                                referenceDescriptor = referenceDetection.descriptor;
                            }
                        }
                    } catch (error) {
                        console.error("Gagal memuat model FaceAPI:", error);
                        updateStatus('Gagal memuat model AI. Pastikan file model ada di folder public/models.', 'bg-red-100', 'text-red-700'); return;
                    }
                }
                startCameraStream();
            }
            
            function startCameraStream() {
                isProcessing = false;
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } }).then(stream => {
                    video.srcObject = stream;
                    videoStream = stream;
                }).catch(err => updateStatus('Tidak dapat mengakses kamera. Harap izinkan akses.', 'bg-red-100', 'text-red-700'));
            }

           video.addEventListener('play', () => {
    // Definisi dan reset state utama
    let livenessStep = 'match';
    const challenges = ['turn_left', 'turn_right']; // <-- 'blink' ditambahkan
    //const challenges = ['nod', 'turn_left', 'turn_right', 'blink']; // <-- 'blink' ditambahkan

    let challengeQueue = [];
    let challengeIndex = 0;

    // State untuk setiap tantangan
    let nodState = 'initial', initialY = null, eyeState = 'open', blinkFrameCounter = 0;

    // Fungsi bantu untuk maju ke tantangan berikutnya
    function advanceToNextChallenge() {
        challengeIndex++;
        nodState = 'initial', initialY = null, eyeState = 'open', blinkFrameCounter = 0; // Reset semua state
        if (challengeIndex >= challengeQueue.length) {
            livenessStep = 'smile';
        } else {
            livenessStep = challengeQueue[challengeIndex];
        }
    }
    
    detectionInterval = setInterval(async () => {
        if (isProcessing) return;

        const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceExpressions().withFaceDescriptors();
        
        if (detections.length === 0) {
            updateStatus('Wajah tidak terdeteksi. Posisikan wajah di depan kamera.', 'bg-blue-100', 'text-blue-700');
            livenessStep = 'match', challengeQueue = [], challengeIndex = 0;
            return;
        }

        const landmarks = detections[0].landmarks;

        // LANGKAH 1: PENCOCOKAN WAJAH
        if (livenessStep === 'match') {
            updateStatus('Mencocokkan wajah...', 'bg-blue-100', 'text-blue-700');
            if (referenceDescriptor) {
                const faceMatcher = new faceapi.FaceMatcher(referenceDescriptor, 0.5);
                const bestMatch = faceMatcher.findBestMatch(detections[0].descriptor);
                if (bestMatch.label !== 'unknown') {
                    challengeQueue = shuffle([...challenges]);
                    livenessStep = challengeQueue[0];
                } else {
                    updateStatus('Wajah tidak cocok!', 'bg-red-100', 'text-red-700');
                }
            } else {
                 challengeQueue = shuffle([...challenges]);
                 livenessStep = challengeQueue[0];
            }
        }
        
        // TANTANGAN: ANGGUKAN
        else if (livenessStep === 'nod') {
            updateStatus('Untuk verifikasi, silakan ANGGUKKAN kepala.', 'bg-yellow-100', 'text-yellow-700');
            const nose = landmarks.getNose();
            const currentY = nose[3].y;
            if (initialY === null) initialY = currentY;
            if (currentY > initialY + 7 && nodState === 'initial') {
                nodState = 'down';
            } else if (currentY < initialY && nodState === 'down') {
                advanceToNextChallenge();
            }
        }

        // TANTANGAN: GELENG KIRI
        else if (livenessStep === 'turn_left') {
            updateStatus('Untuk verifikasi, silakan gelengkan kepala ke KIRI.', 'bg-yellow-100', 'text-yellow-700');
            const jawOutline = landmarks.getJawOutline();
            const nose = landmarks.getNose();
            const nosePositionRatio = (nose[3].x - jawOutline[0].x) / (jawOutline[16].x - jawOutline[0].x);
            if (nosePositionRatio > 0.75) {
                advanceToNextChallenge();
            }
        }

        // TANTANGAN: GELENG KANAN
        else if (livenessStep === 'turn_right') {
            updateStatus('Untuk verifikasi, silakan gelengkan kepala ke KANAN.', 'bg-yellow-100', 'text-yellow-700');
            const jawOutline = landmarks.getJawOutline();
            const nose = landmarks.getNose();
            const nosePositionRatio = (nose[3].x - jawOutline[0].x) / (jawOutline[16].x - jawOutline[0].x);
            if (nosePositionRatio < 0.25) {
                advanceToNextChallenge();
            }
        }

        // TANTANGAN: KEDIP MATA (BARU)
        else if (livenessStep === 'blink') {
            updateStatus('Untuk verifikasi, silakan BERKEDIP.', 'bg-yellow-100', 'text-yellow-700');
            const ear = (getEyeAspectRatio(landmarks.getLeftEye()) + getEyeAspectRatio(landmarks.getRightEye())) / 2.0;
            
            if (ear < EYE_AR_THRESH && eyeState === 'open') {
                eyeState = 'closing';
                blinkFrameCounter = 1;
            } else if (eyeState === 'closing') {
                if (ear < EYE_AR_THRESH) {
                    blinkFrameCounter++;
                } else if (ear > EYE_AR_THRESH) {
                    if (blinkFrameCounter > 0 && blinkFrameCounter < 4) {
                        advanceToNextChallenge();
                    } else {
                        eyeState = 'open';
                        blinkFrameCounter = 0;
                    }
                }
            }
            if (blinkFrameCounter > 4) {
                updateStatus('Kedipan tidak valid, coba lagi.', 'bg-blue-100', 'text-blue-700');
                eyeState = 'open';
                blinkFrameCounter = 0;
            }
        }

        // LANGKAH TERAKHIR: SENYUM
        else if (livenessStep === 'smile') {
            updateStatus('Verifikasi berhasil! ✅ Terakhir, silakan tersenyum.', 'bg-green-100', 'text-green-700');
            if (detections[0].expressions.happy > 0.8) {
                takePhotoAndShowPreview();
            }
        }

    }, 300);
});
            
            function shuffle(array) {
                for (let i = array.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [array[i], array[j]] = [array[j], array[i]];
                }
                return array;
            }
            function takePhotoAndShowPreview() {
                if (isProcessing) return;
                isProcessing = true;
                clearInterval(detectionInterval);
                updateStatus('Senyum terdeteksi! Foto berhasil diambil.', 'bg-green-100', 'text-green-700');
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);
                canvas.toBlob(blob => {
                    const file = new File([blob], 'selfie.jpg', { type: 'image/jpeg' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    photoInput.files = dataTransfer.files;
                    photoInput.dispatchEvent(new Event('change'));
                }, 'image/jpeg');
                if (videoStream) { videoStream.getTracks().forEach(track => track.stop()); }
                step3_camera.classList.add('hidden');
                step4_preview.classList.remove('hidden');
            }

            photoInput.addEventListener('change', (event) => {
                if(event.target.files && event.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        selfiePreview.src = e.target.result;
                        const now = new Date();
                        const dateTimeString = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) + ' ' + now.toLocaleTimeString('id-ID');
                        selfieInfo.innerHTML = `
                            <p><strong>Nama:</strong> ${userName}</p>
                            <p><strong>Lokasi:</strong> ${latitudeInput.value}, ${longitudeInput.value}</p>
                            <p><strong>Waktu:</strong> ${dateTimeString}</p>
                        `;
                    }
                    reader.readAsDataURL(event.target.files[0]);
                } else { window.location.reload(); }
            });

            btnRetake.addEventListener('click', () => {
                photoInput.value = null;
                step4_preview.classList.add('hidden');
                step3_camera.classList.remove('hidden');
                startCameraStream();
            });

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                btnSubmit.disabled = true;
                btnSubmit.textContent = 'Mengirim...';
                const formData = new FormData(form);
                try {
                    const response = await fetch("{{ route('absensi.store') }}", {
                        method: 'POST', body: formData,
                        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'Accept': 'application/json', }
                    });
                    const result = await response.json();
                    if (result.success) { showModal(true, result.message); } 
                    else { showModal(false, result.message || 'Terjadi kesalahan di server.'); }
                } catch (error) { showModal(false, 'Terjadi kesalahan jaringan. Periksa koneksi Anda.');
                } finally {
                    btnSubmit.disabled = false;
                    btnSubmit.textContent = 'Kirim Absen';
                }
            });
        });
    </script>
</x-app-layout>
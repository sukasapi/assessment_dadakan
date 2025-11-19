@extends('admin.layouts.app')

@section('title', 'Daftar Sesi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Daftar Sesi</h1>
            <p class="text-gray-600 mt-2">Kelola sesi penilaian assessment</p>
        </div>
        <a href="{{ route('admin.sesi.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Buat Sesi Baru
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    


    <!-- Sessions List -->
    <div class="bg-white shadow rounded-lg">
        @if($sesiList->count() > 0)
            <div class="overflow-x-auto">
                                 <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                                                         <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">
                                 Nama Sesi
                             </th>
                             <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                 Status
                             </th>
                             <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">
                                 Durasi
                             </th>
                             <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">
                                 Assessment
                             </th>
                             <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/8">
                                 Dibuat
                             </th>
                             <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                                 Aksi
                             </th>
                             </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sesiList as $sesi)
                            <tr class="hover:bg-gray-50">
                                                                                                  <td class="px-4 py-4">
                                     <div class="text-sm font-medium text-gray-900">{{ $sesi->nama }}</div>
                                     @if($sesi->catatan)
                                         <div class="text-sm text-gray-500">{{ Str::limit(strip_tags($sesi->catatan), 30) }}</div>
                                     @endif
                                 </td>
                                 <td class="px-4 py-4 whitespace-nowrap">
                                     <select id="status-{{ $sesi->id }}" 
                                             class="status-select text-xs font-semibold rounded-full px-3 py-1.5 border-0 focus:ring-2 focus:ring-blue-500 transition-colors duration-200
                                                 @if($sesi->status === 'draft') bg-gray-100 text-gray-700 hover:bg-gray-200
                                                 @elseif($sesi->status === 'pending') bg-yellow-100 text-yellow-700 hover:bg-yellow-200
                                                 @elseif($sesi->status === 'active') bg-green-100 text-green-700 hover:bg-green-200
                                                 @elseif($sesi->status === 'paused') bg-orange-100 text-orange-700 hover:bg-orange-200
                                                 @else bg-blue-100 text-blue-700 hover:bg-blue-200
                                                 @endif"
                                             data-sesi-id="{{ $sesi->id }}">
                                         <option value="draft" @if($sesi->status === 'draft') selected @endif>Draft</option>
                                         <option value="pending" @if($sesi->status === 'pending') selected @endif>Menunggu</option>
                                         <option value="active" @if($sesi->status === 'active') selected @endif>Aktif</option>
                                         <option value="paused" @if($sesi->status === 'paused') selected @endif>Dijeda</option>
                                         <option value="completed" @if($sesi->status === 'completed') selected @endif>Selesai</option>
                                     </select>
                                 </td>
                                 <td class="px-4 py-4 text-sm text-gray-900">
                                     @if($sesi->durasi_menit)
                                         {{ $sesi->durasi_menit }} menit
                                     @else
                                         <span class="text-gray-400">-</span>
                                     @endif
                                 </td>
                                 <td class="px-4 py-4">
                                     <div class="text-sm text-gray-900 mb-2">
                                         {{ $sesi->assessments->count() }} assessment
                                     </div>
                                     <div class="space-y-2">
                                         @foreach($sesi->assessments as $assessment)
                                             <div class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">
                                                 {{ $assessment->urutan }}. {{ Str::limit(strip_tags($assessment->penilaian->nama), 20) }}
                                             </div>
                                         @endforeach
                                     </div>
                                 </td>
                                 <td class="px-4 py-4 text-sm text-gray-500">
                                     {{ $sesi->created_at->format('d/m/Y') }}
                                 </td>
                                 <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="space-y-2">
                                        <a href="{{ route('admin.sesi.show', $sesi->id) }}" 
                                           class="block w-full text-center bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs px-3 py-2 rounded-md font-medium transition-colors duration-200">
                                            Lihat
                                        </a>
                                        <a href="{{ route('admin.sesi.peserta', $sesi->id) }}" 
                                           class="block w-full text-center bg-green-100 hover:bg-green-200 text-green-700 text-xs px-3 py-2 rounded-md font-medium transition-colors duration-200">
                                            Peserta
                                        </a>
                                        <a href="{{ route('admin.progress.answers', ['sesi_id' => $sesi->id]) }}" 
                                           class="block w-full text-center bg-purple-100 hover:bg-purple-200 text-purple-700 text-xs px-3 py-2 rounded-md font-medium transition-colors duration-200">
                                            Progres Pengisian
                                        </a>
                                                                                 <a href="{{ route('admin.sesi.edit', $sesi->id) }}" 
                                            class="block w-full text-center bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-xs px-3 py-2 rounded-md font-medium transition-colors duration-200">
                                             Edit
                                         </a>
                                        <form action="{{ route('admin.sesi.destroy', $sesi->id) }}" 
                                              method="POST" 
                                              class="block"
                                              onsubmit="return confirmDelete('Apakah Anda yakin ingin menghapus sesi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full text-center bg-red-100 hover:bg-red-200 text-red-700 text-xs px-3 py-2 rounded-md font-medium transition-colors duration-200">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada sesi</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat sesi pertama Anda.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.sesi.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Buat Sesi Baru
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status update functionality
    const statusSelects = document.querySelectorAll('.status-select');
    
    if (statusSelects.length === 0) {
        return;
    }
    
        statusSelects.forEach((select, index) => {
        // Store original status and apply initial styling
        select.setAttribute('data-original-status', select.value);
        updateStatusAppearance(select, select.value);
        
        select.addEventListener('change', function() {
            const sesiId = this.getAttribute('data-sesi-id');
            const newStatus = this.value;
            const originalStatus = this.getAttribute('data-original-status') || this.value;
            
            // Update visual appearance immediately
            updateStatusAppearance(this, newStatus);
            
            // Send AJAX request to update status
            
            fetch(`/admin/sesi/${sesiId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification(data.message, 'success');
                    
                    // Update the select element with new status label
                    this.setAttribute('data-original-status', newStatus);
                    
                    // Update visual appearance with new status label
                    updateStatusAppearance(this, newStatus, data.status_label);
                } else {
                    // Revert to original status on error
                    this.value = originalStatus;
                    updateStatusAppearance(this, originalStatus);
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                // Revert to original status on error
                this.value = originalStatus;
                updateStatusAppearance(this, originalStatus);
                showNotification('Terjadi kesalahan saat mengupdate status.', 'error');
            });
        });
    });
    
    // Function to update status appearance
    function updateStatusAppearance(select, status, statusLabel = null) {
        // Remove all status-specific classes
        const classesToRemove = [
            'bg-gray-100', 'text-gray-700', 'hover:bg-gray-200',
            'bg-yellow-100', 'text-yellow-700', 'hover:bg-yellow-200',
            'bg-green-100', 'text-green-700', 'hover:bg-green-200',
            'bg-orange-100', 'text-orange-700', 'hover:bg-orange-200',
            'bg-blue-100', 'text-blue-700', 'hover:bg-blue-200'
        ];
        
        select.classList.remove(...classesToRemove);
        
        // Add appropriate classes based on status
        let newClasses = [];
        switch(status) {
            case 'draft':
                newClasses = ['bg-gray-100', 'text-gray-700', 'hover:bg-gray-200'];
                break;
            case 'pending':
                newClasses = ['bg-yellow-100', 'text-yellow-700', 'hover:bg-yellow-200'];
                break;
            case 'active':
                newClasses = ['bg-green-100', 'text-green-700', 'hover:bg-green-200'];
                break;
            case 'paused':
                newClasses = ['bg-orange-100', 'text-orange-700', 'hover:bg-orange-200'];
                break;
            case 'completed':
                newClasses = ['bg-blue-100', 'text-blue-700', 'hover:bg-blue-200'];
                break;
            default:
                newClasses = ['bg-gray-100', 'text-gray-700', 'hover:bg-gray-200'];
        }
        
        select.classList.add(...newClasses);
    }
    
    // Function to show notifications
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-xl transform transition-all duration-300 ease-in-out translate-x-full opacity-0 ${
            type === 'success' 
                ? 'bg-green-500 text-white border-l-4 border-green-600' 
                : 'bg-red-500 text-white border-l-4 border-red-600'
        }`;
        
        // Add icon based on type
        const icon = type === 'success' 
            ? '<svg class="w-5 h-5 mr-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
            : '<svg class="w-5 h-5 mr-3 inline" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
        
        notification.innerHTML = icon + message;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.add('translate-x-0', 'opacity-100');
        }, 100);
        
        // Remove after 4 seconds with animation
        setTimeout(() => {
            notification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 4000);
    }
    
    // Flash notifications on page load
    const flashSuccess = @json(session('success'));
    const flashError = @json(session('error'));
    if (flashSuccess) {
        showNotification(flashSuccess, 'success');
    }
    if (flashError) {
        showNotification(flashError, 'error');
    }

});

// Confirm delete function
function confirmDelete(message) {
    return confirm(message);
}


</script>
@endpush

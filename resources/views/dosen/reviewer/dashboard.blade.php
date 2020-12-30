<div class="card">
    <div class="card-header" data-background-color="green">
        <h4>Ringkasan Review</h4>
    </div>
    
    <div class="card-content">
        <div class="alert alert-info">
            <h6><strong>Tahap 1</strong></h6>
            @if($daftarproposal['tahap1']->count() > 0)
            Anda sudah menilai {{ $daftarproposal['sudahdinilai']['tahap1'] }} dari {{ $daftarproposal['tahap1']->count() }} proposal
            @else
            Tidak ada proposal untuk direview pada tahap 1
            @endif
        </div>
        
        <div class="alert alert-info">
            <h6><strong>Tahap 2</strong></h6>
            @if($daftarproposal['tahap2']->count() > 0)
            Anda sudah menilai {{ $daftarproposal['sudahdinilai']['tahap2'] }} dari {{ $daftarproposal['tahap2']->count() }} proposal        
            @else
            Tidak ada proposal untuk direview pada tahap 2
            @endif
        </div>
    </div>
</div>
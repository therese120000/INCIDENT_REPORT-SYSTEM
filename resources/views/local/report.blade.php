<div class="page report-page" id="report">
    <div class="form-card">

        <div class="form-header">
            <h2>Report an Issue</h2>
            <p>Please provide the details of the incident.</p>
        </div>

        <form id="incidentReportForm" enctype="multipart/form-data">

            <!-- ISSUE TYPE -->
            <div class="form-group">
                <label for="issueType">Issue Type</label>

                <select id="issueType" name="incident_type_id" required>
                    <option value="">Select issue type</option>

                    @foreach($incidentTypes as $incidentType)
                        <option value="{{ $incidentType->id }}">
                            {{ $incidentType->name }}
                        </option>
                    @endforeach

                </select>
            </div>


            <!-- TITLE -->
            <div class="form-group">
                <label for="title">Title</label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    placeholder="Enter the title of the incident"
                    required
                >
            </div>


            <!-- DESCRIPTION -->
            <div class="form-group">
                <label for="description">Description</label>

                <textarea
                    id="description"
                    name="description"
                    rows="6"
                    placeholder="Describe the issue or incident..."
                    required
                ></textarea>
            </div>


            <!-- LOCATION -->
            <div class="form-group">
                <label for="location">Location</label>

                <input
                    type="text"
                    id="location"
                    name="location"
                    placeholder="Enter the specific location of the incident"
                    required
                >
            </div>


            <!-- PUROK -->
            <div class="form-group">
                <label for="purok">Purok</label>

                <select id="purok" name="purok" required>
                    <option value="">Select Purok</option>
                    <option value="Purok 1">Purok 1</option>
                    <option value="Purok 2">Purok 2</option>
                    <option value="Purok 3">Purok 3</option>
                    <option value="Purok 4">Purok 4</option>
                    <option value="Purok 5">Purok 5</option>
                    <option value="Purok 6">Purok 6</option>
                    <option value="Purok 7">Purok 7</option>
                    <option value="Purok 8">Purok 8</option>
                    <option value="Purok 9">Purok 9</option>
                    <option value="Purok 10">Purok 10</option>
                    <option value="Purok 11">Purok 11</option>
                    <option value="Purok 12">Purok 12</option>
                </select>
            </div>


            <!-- WATER LEVEL -->
            <div class="form-group">
                <label for="waterLevel">Water Level</label>

                <select id="waterLevel" name="water_level" required>
                    <option value="">Select water level</option>
                    <option value="LOW">LOW</option>
                    <option value="MODERATE">MODERATE</option>
                    <option value="HIGH">HIGH</option>
                </select>
            </div>


            <!-- SEVERITY -->
            <div class="form-group">
                <label for="severity">Severity</label>

                <select id="severity" name="severity" required>
                    <option value="">Select severity</option>
                    <option value="LOW">LOW</option>
                    <option value="MEDIUM">MEDIUM</option>
                    <option value="HIGH">HIGH</option>
                </select>
            </div>


            <!-- PHOTO -->
            <div class="form-group">
                <label for="photo">
                    Photo / Evidence
                </label>

                <div class="upload-box">

                    <input
                        type="file"
                        id="photo"
                        name="photo"
                        accept="image/jpeg,image/png,image/jpg"
                    >

                    <div class="upload-content">
                        <span class="upload-icon">📷</span>
                        <strong>Upload a photo</strong>
                        <span>JPG, PNG up to 5MB</span>
                    </div>

                </div>
            </div>


            <!-- SUBMIT -->
            <div class="form-actions">
                <button
                    type="reset"
                    class="secondary-btn">
                    Clear
                </button>

                <button
                    type="submit"
                    class="primary-btn">
                    Submit Report
                </button>
            </div>

        </form>
    </div>
</div>
    <script src="{{ asset('js/submitReport.js')}}"></script>

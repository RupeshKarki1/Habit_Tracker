<div
    class="modal"
    id="edit-habit-modal"
    aria-hidden="true"
>

    <!-- Dark background -->
    <div
        class="modal-backdrop"
        data-close-edit-modal
    ></div>

    <!-- Modal box -->
    <div
        class="modal-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="edit-habit-modal-title"
    >

        <div class="modal-header">

            <div>
                <h2 id="edit-habit-modal-title">
                    Edit Habit
                </h2>

                <p>
                    Update your habit details.
                </p>
            </div>

            <button
                type="button"
                class="modal-close"
                aria-label="Close modal"
                data-close-edit-modal
            >
                &times;
            </button>

        </div>

        <form
            class="habit-form"
            id="edit-habit-form"
            action="update-habit.php"
            method="POST"
        >

            <!-- Hidden habit ID -->
            <input
                type="hidden"
                id="edit-habit-id"
                name="habit_id"
            >

            <!-- Habit name -->
            <div class="form-group">

                <label for="edit-habit-name">
                    Habit Name
                </label>

                <input
                    type="text"
                    id="edit-habit-name"
                    name="habit_name"
                    maxlength="100"
                    required
                >

            </div>

            <!-- Description -->
            <div class="form-group">

                <label for="edit-habit-description">
                    Description (Optional)
                </label>

                <textarea
                    id="edit-habit-description"
                    name="description"
                    rows="3"
                ></textarea>

            </div>

            <!-- Category -->
            <div class="form-group">

                <label for="edit-habit-category">
                    Category
                </label>

                <select
                    id="edit-habit-category"
                    name="category"
                    required
                >

                    <option value="">Select category</option>

                    <option value="health">Health</option>
                    <option value="fitness">Fitness</option>
                    <option value="learning">Learning</option>
                    <option value="productivity">Productivity</option>
                    <option value="other">Other</option>

                </select>

            </div>

            <!-- Frequency -->
            <div class="form-group">

                <label for="edit-habit-frequency">
                    Frequency
                </label>

                <select
                    id="edit-habit-frequency"
                    name="frequency"
                    required
                >

                    <option value="">Select frequency</option>

                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>

                </select>

            </div>

            <!-- Modal buttons -->
            <div class="modal-actions">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-close-edit-modal
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>
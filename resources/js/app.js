const parseSelectedTime = (hourSelect, minuteSelect) => {
    if (!hourSelect.value || !minuteSelect.value) {
        return null;
    }

    return Number.parseInt(hourSelect.value, 10) * 60 + Number.parseInt(minuteSelect.value, 10);
};

const initialiseWorkSessionDurationCalculator = () => {
    document.querySelectorAll('[data-work-session-form]').forEach((form) => {
        const startHourSelect = form.querySelector('[name="start_time_hour"]');
        const startMinuteSelect = form.querySelector('[name="start_time_minute"]');
        const endHourSelect = form.querySelector('[name="end_time_hour"]');
        const endMinuteSelect = form.querySelector('[name="end_time_minute"]');
        const workMinutesInput = form.querySelector('[name="work_minutes"]');

        if (!startHourSelect || !startMinuteSelect || !endHourSelect || !endMinuteSelect || !workMinutesInput) {
            return;
        }

        let previousAutoFilledValue = null;

        const updateWorkMinutes = (force = false) => {
            const startMinutes = parseSelectedTime(startHourSelect, startMinuteSelect);
            const endMinutes = parseSelectedTime(endHourSelect, endMinuteSelect);
            const currentValue = workMinutesInput.value.trim();

            if (startMinutes === null || endMinutes === null || endMinutes <= startMinutes) {
                if (force && previousAutoFilledValue !== null && currentValue === previousAutoFilledValue) {
                    workMinutesInput.value = '';
                    previousAutoFilledValue = null;
                }

                return;
            }

            const calculatedValue = String(endMinutes - startMinutes);

            if (force || currentValue === '' || currentValue === previousAutoFilledValue) {
                workMinutesInput.value = calculatedValue;
                previousAutoFilledValue = calculatedValue;
            }
        };

        [startHourSelect, startMinuteSelect, endHourSelect, endMinuteSelect].forEach((select) => {
            select.addEventListener('change', () => updateWorkMinutes(true));
        });

        workMinutesInput.addEventListener('input', () => {
            if (workMinutesInput.value.trim() !== previousAutoFilledValue) {
                previousAutoFilledValue = null;
            }
        });

        updateWorkMinutes();
    });
};

document.addEventListener('DOMContentLoaded', initialiseWorkSessionDurationCalculator);
